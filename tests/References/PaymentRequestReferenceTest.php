<?php

namespace AvtoDev\MonetaApi\Tests\References;

use AvtoDev\MonetaApi\References\PaymentRequestReference;

class PaymentRequestReferenceTest extends BaseReferenceTestCase
{
    protected $constants = [
        'amount',
        'clientTransaction',
        'description',
        'exitOnFailure',
        'isPayerAmount',
        'operationInfo',
        'payee',
        'payer',
        'paymentPassword',
        'personalInformation',
        'transaction',
        'transactional',

    ];

    protected function getAll()
    {
        return PaymentRequestReference::getAll();
    }
}
