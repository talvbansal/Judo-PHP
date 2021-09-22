<?php

namespace Judopay\Model\Inner;

class PkPayment extends TransmittedField
{
    protected static $fieldName = 'pkPayment';
    protected $requiredAttributes
        = array(
            'token',
            'token.paymentInstrumentName',
            'token.paymentNetwork',
            'token.paymentData',
        );
}
