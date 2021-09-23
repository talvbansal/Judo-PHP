<?php
namespace Judopay\Model\Inner;

class GooglePayWallet extends TransmittedField
{
    protected static $fieldName = 'googlePayWallet';
    protected $requiredAttributes
        = array(
            'cardNetwork',
            'cardDetails',
            'token'
        );
}
