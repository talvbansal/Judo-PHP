<?php

namespace Tests;

use Tests\Builders\CardPaymentBuilder;
use Tests\Helpers\AssertionHelper;
use Tests\Helpers\ConfigHelper;

class AuthenticationTest extends TestCase
{
    public function testPaymentWithInvalidJudoId()
    {
        $config = ConfigHelper::getBaseConfig();

        $builder = new CardPaymentBuilder();
        $builder->setAttribute('judoId', '123');
        $cardPayment = $builder->build($config);

        try {
            $cardPayment->create();
        } catch (\Exception $e) {
            AssertionHelper::assertApiExceptionWithModelErrors(
                $e,
                1,
                1
            );

            return;
        }

        $this->fail('An expected ApiException has not been raised.');
    }

    public function testPaymentWithInvalidToken()
    {
        $config = ConfigHelper::getBaseConfig(array('apiToken' => 'Bad_token'));

        $builder = new CardPaymentBuilder();
        $cardPayment = $builder->build($config);

        try {
            $cardPayment->create();
        } catch (\Exception $e) {
            AssertionHelper::assertApiExceptionWithModelErrors($e, 0, 403, 403, 1);

            return;
        }

        $this->fail('An expected ApiException has not been raised.');
    }

    public function testPaymentWithInvalidSecret()
    {
        $config = ConfigHelper::getBaseConfig(array('apiSecret' => 'Bad_secret'));

        $builder = new CardPaymentBuilder();
        $cardPayment = $builder->build($config);

        try {
            $cardPayment->create();
        } catch (\Exception $e) {
            AssertionHelper::assertApiExceptionWithModelErrors($e, 0, 403, 403, 1);

            return;
        }

        $this->fail('An expected ApiException has not been raised.');
    }
}
