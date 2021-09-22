<?php
namespace Judopay\Model\Inner;

class PrimaryAccountDetails extends TransmittedField
{
    protected static $fieldName = 'primaryAccountDetails';
    protected $requiredAttributes
        = array(
            'name',
            'accountNumber',
            'dateOfBirth',
            'postCode',
        );
}
