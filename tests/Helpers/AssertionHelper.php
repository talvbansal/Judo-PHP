<?php

namespace Tests\Helpers;

use Judopay\Exception\ApiException;
use PHPUnit\Framework\Assert;

class AssertionHelper
{
    public static function assertApiExceptionWithModelErrors(
        $apiException,
        $numberOfModelErrorsExpected,
        $errorCode,
        $statusCode = 400,
        $errorCategory = 2
    ) {
        /** @var ApiException $apiException */
        Assert::assertInstanceOf(
            '\Judopay\Exception\ApiException',
            $apiException
        );

        Assert::assertCount(
            $numberOfModelErrorsExpected,
            $apiException->getFieldErrors()
        );

        Assert::assertEquals($errorCode, $apiException->getCode());
        Assert::assertEquals($statusCode, $apiException->getHttpStatusCode());
        Assert::assertEquals($errorCategory, $apiException->getCategory());
    }

    public static function assertSuccessfulPayment($result)
    {
        Assert::assertNotNull($result);
        Assert::assertEquals('Success', $result['result']);
        Assert::assertGreaterThan(0, $result['receiptId']);
    }

    public static function assertDeclinedPayment($result)
    {
        Assert::assertNotNull($result);
        Assert::assertEquals('Declined', $result['result']);
        Assert::assertGreaterThan(0, $result['receiptId']);
    }

    public static function assertRequiresThreeDSecure($result)
    {
        Assert::assertNotNull($result);
        Assert::assertEquals('Requires 3D Secure', $result['result']);
        Assert::assertGreaterThan(0, $result['receiptId']);
    }

    public static function assertRequiresThreeDSecureTwoDeviceDetails($result)
    {
        Assert::assertNotNull($result);
        Assert::assertEquals('Additional device data is needed for 3D Secure 2', $result['result']);
        Assert::assertEquals('Issuer ACS has requested additional device data gathering', $result['message']);
        Assert::assertNotNull($result['methodUrl']);
        Assert::assertNotNull($result['version']);
        Assert::assertGreaterThan(0, $result['receiptId']);
    }

    public static function assertRequiresThreeDSecureTwoChallengeCompletion($result)
    {
        Assert::assertNotNull($result);
        Assert::assertEquals('Challenge completion is needed for 3D Secure 2', $result['result']);
        Assert::assertEquals('Issuer ACS has responded with a Challenge URL', $result['message']);
        Assert::assertNotNull($result['challengeUrl']);
        Assert::assertNotNull($result['version']);
        Assert::assertNotNull($result['md']);
        Assert::assertNotNull($result['cReq']);
        Assert::assertGreaterThan(0, $result['receiptId']);
    }

    public static function assertAuthCodeAvailable($result)
    {
        Assert::assertNotNull($result['authCode']);
    }

    public static function assertSuccessfulGetReceipt($receipt)
    {
        Assert::assertNotNull($receipt);
        Assert::assertEquals('Success', $receipt['result']);
        Assert::assertGreaterThan(0, $receipt['receiptId']);
        Assert::assertNotNull($receipt['acquirer']);
    }

    public static function assertSuccessfulWebPaymentCreation($result)
    {
        Assert::assertNotNull($result);
        Assert::assertNotNull($result['payByLinkUrl']);
        Assert::assertNotNull($result['postUrl']);
        Assert::assertNotNull($result['reference']);
    }

    public static function assertSuccessfulGetWebPaymentReceipt($receipt)
    {
        AssertionHelper::assertSuccessfulGetReceipt($receipt);
        Assert::assertNotNull($receipt['webPaymentReference']);
    }
}
