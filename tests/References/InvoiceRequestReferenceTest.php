<?php

namespace AvtoDev\MonetaApi\Tests\References;

use AvtoDev\MonetaApi\References\InvoiceRequestReference;

class InvoiceRequestReferenceTest extends BaseReferenceTestCase
{
    protected $constants = [
        'amount',
        'operationInfo',
        'payee',
        'clientTransaction',
        'payer',
    ];

    protected function getAll()
    {
        return InvoiceRequestReference::getAll();
    }
}
