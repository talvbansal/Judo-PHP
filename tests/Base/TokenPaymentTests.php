<?php

namespace Tests\Base;

use Judopay\Exception\ValidationError;
use Tests\Builders\CardPaymentBuilder;
use Tests\Builders\TokenPaymentBuilder;
use Tests\Builders\TokenPreauthBuilder;
use Tests\Helpers\AssertionHelper;
use Tests\Helpers\ConfigHelper;
use Tests\TestCase;

abstract class TokenPaymentTests extends TestCase
{
    const CONSUMER_REFERENCE = '1234512345';
    protected $cardToken;
    protected $consumerToken;

    /**
     * @return TokenPaymentBuilder|TokenPreauthBuilder
     */
    abstract protected function getBuilder();

    public function setUp() : void
    {
        parent::setUp();

        $builder = new CardPaymentBuilder();
        $cardPayment = $builder->setAttribute('yourConsumerReference', self::CONSUMER_REFERENCE)
            ->build(ConfigHelper::getBaseConfig());

        $result = $cardPayment->create();

        AssertionHelper::assertSuccessfulPayment($result);

        $this->cardToken = $result['cardDetails']['cardToken'];
        $this->consumerToken = $result['consumer']['consumerToken'];
    }

    public function testValidTokenPayment()
    {
        $tokenPayment = $this->getBuilder()
            ->build(ConfigHelper::getBaseConfig());
        $result = $tokenPayment->create();

        AssertionHelper::assertSuccessfulPayment($result);
    }

    public function testDeclinedTokenPayment()
    {
        $tokenPayment = $this->getBuilder()
            ->setAttribute('cv2', '666')
            ->build(ConfigHelper::getBaseConfig());

        $result = $tokenPayment->create();

        AssertionHelper::assertDeclinedPayment($result);
    }

    public function testTokenPaymentWithoutToken()
    {
        $this->expectException(ValidationError::class);

        $tokenPayment = $this->getBuilder()
            ->unsetAttribute('cardToken')
            ->build(ConfigHelper::getBaseConfig());

        $tokenPayment->create();
    }

    public function testTokenPaymentWithoutCv2AndWithoutToken()
    {
        $this->expectException(ValidationError::class);

        $tokenPayment = $this->getBuilder()
            ->unsetAttribute('cardToken')
            ->unsetAttribute('cv2')
            ->build(ConfigHelper::getBaseConfig());

        $tokenPayment->create();
    }

    public function testTokenPaymentWithNegativeAmount()
    {
        $tokenPayment = $this->getBuilder()
            ->setAttribute('amount', -1)
            ->build(ConfigHelper::getBaseConfig());

        try {
            $tokenPayment->create();
        } catch (\Exception $e) {
            AssertionHelper::assertApiExceptionWithModelErrors($e, 1, 1);

            return;
        }

        $this->fail('An expected ApiException has not been raised.');
    }

    public function testTokenPaymentWithZeroAmount()
    {
        $tokenPayment = $this->getBuilder()
            ->setAttribute('amount', 0)
            ->build(ConfigHelper::getBaseConfig());

        try {
            $tokenPayment->create();
        } catch (\Exception $e) {
            AssertionHelper::assertApiExceptionWithModelErrors($e, 1, 1);

            return;
        }

        $this->fail('An expected ApiException has not been raised.');
    }

    public function testTokenPaymentWithoutCurrency()
    {
        $tokenPayment = $this->getBuilder()
            ->unsetAttribute('currency')
            ->build(ConfigHelper::getBaseConfig());

        $result = $tokenPayment->create();

        AssertionHelper::assertSuccessfulPayment($result);
    }

    public function testTokenPaymentWithUnknownCurrency()
    {
        $tokenPayment = $this->getBuilder()
            ->setAttribute('currency', 'ZZZ')
            ->build(ConfigHelper::getBaseConfig());

        try {
            $tokenPayment->create();
        } catch (\Exception $e) {
            AssertionHelper::assertApiExceptionWithModelErrors($e, 2, 1);

            return;
        }

        $this->fail('An expected ApiException has not been raised.');
    }

    public function testTokenPaymentWithoutReference()
    {
        $this->expectException(ValidationError::class);

        $tokenPayment = $this->getBuilder()
            ->unsetAttribute('yourConsumerReference')
            ->build(ConfigHelper::getBaseConfig());

        $tokenPayment->create();
    }

    /**
     * @group threedsecure
     */
    public function testTokenPaymentWithoutCv2()
    {
        $tokenPayment = $this->getBuilder()
            ->unsetAttribute('cv2')
            ->build(ConfigHelper::getBaseConfig());

        try {
            $tokenPayment->create();
        } catch (\Exception $e) {
            AssertionHelper::assertApiExceptionWithModelErrors($e, 1, 1);
            return;
        }

        $this->fail('An expected ApiException has not been raised.');
    }

    public function testTokenPaymentWithoutCv2AndWithNegativeAmount()
    {
        $tokenPayment = $this->getBuilder()
            ->setAttribute('amount', -1)
            ->build(ConfigHelper::getBaseConfig());

        try {
            $tokenPayment->create();
        } catch (\Exception $e) {
            AssertionHelper::assertApiExceptionWithModelErrors($e, 1, 1);

            return;
        }

        $this->fail('An expected ApiException has not been raised.');
    }

    public function testTokenPaymentWithoutCv2AndWithZeroAmount()
    {
        $tokenPayment = $this->getBuilder()
            ->setAttribute('amount', 0)
            ->unsetAttribute('cv2')
            ->build(ConfigHelper::getBaseConfig());

        try {
            $tokenPayment->create();
        } catch (\Exception $e) {
            AssertionHelper::assertApiExceptionWithModelErrors($e, 1, 1);

            return;
        }

        $this->fail('An expected ApiException has not been raised.');
    }

    /**
     * @group threedsecure
     */
    public function testTokenPaymentWithoutCv2AndWithoutCurrency()
    {
        $tokenPayment = $this->getBuilder()
            ->unsetAttribute('currency')
            ->unsetAttribute('cv2')
            ->build(ConfigHelper::getBaseConfig());

        try {
            $tokenPayment->create();
        } catch (\Exception $e) {
            AssertionHelper::assertApiExceptionWithModelErrors($e, 1, 1);
            return;
        }

        $this->fail('An expected ApiException has not been raised.');
    }

    public function testTokenPaymentWithoutCv2AndWithUnknownCurrency()
    {
        $tokenPayment = $this->getBuilder()
            ->setAttribute('currency', 'ZZZ')
            ->unsetAttribute('cv2')
            ->build(ConfigHelper::getBaseConfig());

        try {
            $tokenPayment->create();
        } catch (\Exception $e) {
            AssertionHelper::assertApiExceptionWithModelErrors($e, 2, 1);

            return;
        }

        $this->fail('An expected ApiException has not been raised.');
    }

    public function testTokenPaymentWithoutCv2AndWithoutReference()
    {
        $this->expectException(ValidationError::class);

        $tokenPayment = $this->getBuilder()
            ->unsetAttribute('yourConsumerReference')
            ->unsetAttribute('cv2')
            ->build(ConfigHelper::getBaseConfig());

        $tokenPayment->create();
    }

    public function testDuplicatePayment()
    {
        $tokenPayment = $this->getBuilder()
            ->build(ConfigHelper::getBaseConfig());
        $successfulResult = $tokenPayment->create();

        try {
            $tokenPayment->create();
        } catch (\Exception $e) {
            AssertionHelper::assertApiExceptionWithModelErrors(
                $e,
                0,
                86,
                409,
                4
            );
                $this->assertStringContainsString(
                    $successfulResult['receiptId'],
                    $e->getMessage()
                );

            return;
        }

        $this->fail('An expected ApiException has not been raised.');
    }
}
