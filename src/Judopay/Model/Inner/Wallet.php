<?php
namespace Judopay\Model\Inner;

class Wallet extends TransmittedField
{
    protected static $fieldName = 'wallet';
    protected $requiredAttributes
        = array(
            'encryptedMessage',
            'environment',
            'ephemeralPublicKey',
            'googleTransactionId',
            'instrumentDetails',
            'instrumentType',
            'publicKey',
            'tag',
            'version',
        );
}
