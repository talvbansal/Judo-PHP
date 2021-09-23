<?php

namespace Tests;

use Judopay\Exception\ValidationError;
use Tests\Builders\CardPaymentBuilder;
use Tests\Helpers\ConfigHelper;

class CardDetailsTest extends JudopayTestCase
{
    public function testPaymentWithMissingCardNumber()
    {
        $this->expectException(ValidationError::class);

        $config = ConfigHelper::getBaseConfig();

        $builder = new CardPaymentBuilder();
        $builder->setAttribute('cardNumber', '');

        $cardPayment = $builder->build($config);
        $cardPayment->create();
    }

    public function testPaymentWithMissingCv2()
    {
        $this->expectException(ValidationError::class);

        $config = ConfigHelper::getBaseConfig();

        $builder = new CardPaymentBuilder();
        $builder->setAttribute('cv2', '');

        $cardPayment = $builder->build($config);

        $cardPayment->create();
    }

    public function testPaymentWithMissingExpiryDate()
    {
        $this->expectException(ValidationError::class);

        $config = ConfigHelper::getBaseConfig();

        $builder = new CardPaymentBuilder();
        $builder->setAttribute('expiryDate', '');

        $cardPayment = $builder->build($config);
        $cardPayment->create();
    }
}
