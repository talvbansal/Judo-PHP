<?php

namespace Tests\Builders;

class RefundBuilder extends AbstractModelBuilder
{
    public function __construct($judoId, $receiptId = '12345', $amount = 1.02)
    {
        $this->attributeValues = array(
            'receiptId'            => $receiptId,
            'amount'               => $amount,
            'judoId'               => $judoId
        );
    }
}
