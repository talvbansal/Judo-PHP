<?php

namespace Tests;

use PHPUnit\Framework\Assert;
use Tests\Base\ThreeDSecureTwoTests;
use Tests\Builders\CardPaymentBuilder;
use Tests\Builders\GetTransactionBuilder;
use Tests\Helpers\AssertionHelper;
use Tests\Helpers\ConfigHelper;

class PaymentTest extends ThreeDSecureTwoTests
{
    protected function getBuilder()
    {
        return new CardPaymentBuilder();
    }

    public function testPaymentFollowedByGetReceipt()
    {
        $paymentResult = $this->getBuilder()
            ->build(ConfigHelper::getCybersourceConfig())
            ->create();

        AssertionHelper::assertSuccessfulPayment($paymentResult);

        $builder = new GetTransactionBuilder();
        $paymentReceipt = $builder->build(ConfigHelper::getCybersourceConfig())
            ->find($paymentResult["receiptId"]);

        AssertionHelper::assertSuccessfulGetReceipt($paymentReceipt);
    }

    public function testBuildRecurringInvalidTypeAttribute()
    {
        $cardPayment = $this->getBuilder()
            ->setAttribute('recurringPaymentType', "aaa");

        try {
            $cardPayment->build(ConfigHelper::getCybersourceConfig());
        } catch (\Exception $e) {
            Assert::assertEquals($e->getMessage(), "Invalid recurring type value");
            return;
        }

        $this->fail('An expected Exception has not been raised.');
    }

    public function testBuildRecurringValidTypeAttribute()
    {
        $cardPayment = $this->getBuilder()
            ->setAttribute('recurringPaymentType', "mit");

        try {
            $cardPayment->build(ConfigHelper::getCybersourceConfig());
        } catch (\Exception $e) {
            $this->fail('An Exception should not have been raised.');
        }

        Assert::assertEquals("mit", $cardPayment->getAttributeValues()["recurringPaymentType"]);
    }

    public function testPaymentWithInvalidChallengeRequestIndicator()
    {
        $cardPayment = $this->getBuilder()
            ->setAttribute('challengeRequestIndicator', "test");
        try {
            $cardPayment->build(ConfigHelper::getCybersourceConfig());
            $this->fail('An expected ValidationError has not been raised.');
            return;
        } catch (\Exception $e) {
            Assert::assertEquals("Invalid challenge indicator value", $e->getMessage());
            return;
        }
    }

    public function testPaymentWithValidChallengeRequestIndicator()
    {
        $cardPayment = $this->getBuilder()
            ->setAttribute('challengeRequestIndicator', "noPreference");
        try {
            $cardPayment->build(ConfigHelper::getCybersourceConfig());
        } catch (\Exception $e) {
            $this->fail('No exception should have been raised.');
            return;
        }

        Assert::assertEquals("noPreference", $cardPayment->getAttributeValues()["challengeRequestIndicator"]);
    }

    public function testPaymentWithInvalidScaExemption()
    {
        $cardPayment = $this->getBuilder()
            ->setAttribute('scaExemption', "test");
        try {
            $cardPayment->build(ConfigHelper::getCybersourceConfig());
            $this->fail('An expected ValidationError has not been raised.');
            return;
        } catch (\Exception $e) {
            Assert::assertEquals("Invalid SCA exemption value", $e->getMessage());
            return;
        }
    }

    public function testPaymentWithValidScaExemption()
    {
        $cardPayment = $this->getBuilder()
            ->setAttribute('scaExemption', "trustedBeneficiary");
        try {
            $cardPayment->build(ConfigHelper::getCybersourceConfig());
        } catch (\Exception $e) {
            $this->fail('No exception should have been raised.');
            return;
        }

        Assert::assertEquals("trustedBeneficiary", $cardPayment->getAttributeValues()["scaExemption"]);
    }
}
