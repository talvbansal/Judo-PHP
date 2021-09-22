<?php
namespace Judopay\Model\Inner;

use Judopay\DataType;

class ThreeDSecureTwo extends TransmittedField
{
    protected static $fieldName = 'threeDSecure';

    protected $attributes
        = array(
            'authenticationSource'      => DataType::TYPE_STRING,
            'methodCompletion'          => DataType::TYPE_STRING,
            'methodNotificationUrl'     => DataType::TYPE_STRING,
            'challengeNotificationUrl'  => DataType::TYPE_STRING,
        );

    protected $requiredAttributes
        = array(
            'authenticationSource'
        );
}
