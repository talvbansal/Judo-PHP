<?php

namespace Tests;

use Tests\Builders\EncryptDetailsBuilder;
use Tests\Builders\OneUseTokenPaymentBuilder;
use Tests\Builders\OneUseTokenPreauthBuilder;
use Tests\Builders\SaveEncryptedCardBuilder;
use Tests\Builders\RegisterEncryptedCardBuilder;
use Tests\Builders\CheckEncryptedCardBuilder;
use Tests\Helpers\AssertionHelper;
use Tests\Helpers\ConfigHelper;

class AdditionsPaymentTest extends JudopayTestCase
{
    public function testOneUseTokenPayment()
    {
        # Generate one use token
        $encryptionResult = $this->generateOneUseToken();
        $oneUseToken = $encryptionResult['oneUseToken'];
        $this->assertNotEmpty($oneUseToken);

        # Make successful payment with one use token
        $tokenPaymentResult = $this->makePaymentWithOneUseToken($oneUseToken);
        $this->assertEquals('Success', $tokenPaymentResult['result']);

        # Try and fail to make a second payment with one use token
        try {
            $this->makePaymentWithOneUseToken($oneUseToken);
        } catch (\Exception $e) {
            AssertionHelper::assertApiExceptionWithModelErrors($e, 0, 153, 400, 1);
            return;
        }
        $this->fail('An expected ApiException has not been raised.');
    }

    public function testOneUseTokenPreauth()
    {
        # Generate one use token
        $encryptionResult = $this->generateOneUseToken();
        $oneUseToken = $encryptionResult['oneUseToken'];
        $this->assertNotEmpty($oneUseToken);

        # Make successful preauth with one use token
        $tokenPreauthResult = $this->makePreauthWithOneUseToken($oneUseToken);
        $this->assertEquals('Success', $tokenPreauthResult['result']);

        # Try and fail to make a second preauth with one use token
        try {
            $this->makePreauthWithOneUseToken($oneUseToken);
        } catch (\Exception $e) {
            AssertionHelper::assertApiExceptionWithModelErrors($e, 0, 153, 400, 1);
            return;
        }
        $this->fail('An expected ApiException has not been raised.');
    }

    public function testOneUseTokenSaveCard()
    {
        # Generate one use token
        $encryptionResult = $this->generateOneUseToken();
        $oneUseToken = $encryptionResult['oneUseToken'];
        $this->assertNotEmpty($oneUseToken);

        # Make successful save card with one use token
        $tokenPaymentResult = $this->saveCardWithOneUseToken($oneUseToken);
        $this->assertEquals('Success', $tokenPaymentResult['result']);

        # Try and fail to save the card with one use token
        try {
            $this->saveCardWithOneUseToken($oneUseToken);
        } catch (\Exception $e) {
            AssertionHelper::assertApiExceptionWithModelErrors($e, 0, 153, 400, 1);
            return;
        }
        $this->fail('An expected ApiException has not been raised.');
    }

    public function testOneUseTokenRegisterCard()
    {
        # Generate one use token
        $encryptionResult = $this->generateOneUseToken();
        $oneUseToken = $encryptionResult['oneUseToken'];
        $this->assertNotEmpty($oneUseToken);

        # Make successful register card with one use token
        $tokenPaymentResult = $this->registerCardWithOneUseToken($oneUseToken);
        $this->assertEquals('Success', $tokenPaymentResult['result']);

        # Try and fail to register the card with one use token
        try {
            $this->registerCardWithOneUseToken($oneUseToken);
        } catch (\Exception $e) {
            AssertionHelper::assertApiExceptionWithModelErrors($e, 0, 153, 400, 1);
            return;
        }
        $this->fail('An expected ApiException has not been raised.');
    }

    public function testOneUseTokenCheckCard()
    {
        # Generate one use token
        # Uses the secondary account that allows £0 authorizations
        $encryptionResult = $this->generateOneUseTokenCyb();
        $oneUseToken = $encryptionResult['oneUseToken'];
        $this->assertNotEmpty($oneUseToken);

        # Make successful check card with one use token
        $tokenPaymentResult = $this->checkCardWithOneUseToken($oneUseToken);
        $this->assertEquals('Success', $tokenPaymentResult['result']);

        # Try and fail to check the card with one use token
        try {
            $this->checkCardWithOneUseToken($oneUseToken);
        } catch (\Exception $e) {
            AssertionHelper::assertApiExceptionWithModelErrors($e, 0, 153, 400, 1);
            return;
        }
        $this->fail('An expected ApiException has not been raised.');
    }

    protected function encryptDetailsRequestBuilder()
    {
        return new EncryptDetailsBuilder();
    }

    protected function generateOneUseToken()
    {
        $encryptDetails = $this->encryptDetailsRequestBuilder()
            ->build(ConfigHelper::getBaseConfig());

        return $encryptDetails->create();
    }

    protected function generateOneUseTokenCyb()
    {
        $encryptDetails = $this->encryptDetailsRequestBuilder()
            ->build(ConfigHelper::getCybersourceConfig());

        return $encryptDetails->create();
    }

    protected function tokenPaymentRequestBuilder()
    {
        return new OneUseTokenPaymentBuilder();
    }

    protected function tokenPreauthRequestBuilder()
    {
        return new OneUseTokenPreauthBuilder();
    }

    protected function saveEncryptedCardRequestBuilder()
    {
        return new SaveEncryptedCardBuilder();
    }

    protected function registerEncryptedCardRequestBuilder()
    {
        return new RegisterEncryptedCardBuilder();
    }

    protected function checkEncryptedCardRequestBuilder()
    {
        return new CheckEncryptedCardBuilder();
    }


    protected function makePaymentWithOneUseToken($token)
    {
        $tokenPayment = $this->tokenPaymentRequestBuilder()
            ->setAttribute('oneUseToken', $token)
            ->build(ConfigHelper::getBaseConfig());
        return $tokenPayment->create();
    }

    protected function makePreauthWithOneUseToken($token)
    {
        $tokenPreauth = $this->tokenPreauthRequestBuilder()
            ->setAttribute('oneUseToken', $token)
            ->build(ConfigHelper::getBaseConfig());
        return $tokenPreauth->create();
    }

    protected function saveCardWithOneUseToken($token)
    {
        $encryptedSaveCard = $this->saveEncryptedCardRequestBuilder()
            ->setAttribute('oneUseToken', $token)
            ->build(ConfigHelper::getBaseConfig());
        return $encryptedSaveCard->create();
    }

    protected function registerCardWithOneUseToken($token)
    {
        $encryptedRegisterCard = $this->registerEncryptedCardRequestBuilder()
            ->setAttribute('oneUseToken', $token)
            ->build(ConfigHelper::getBaseConfig());
        return $encryptedRegisterCard->create();
    }

    protected function checkCardWithOneUseToken($token)
    {
        $encryptedCheckCard = $this->checkEncryptedCardRequestBuilder()
            ->setAttribute('oneUseToken', $token)
            ->build(ConfigHelper::getCybersourceConfig());
        return $encryptedCheckCard->create();
    }
}
