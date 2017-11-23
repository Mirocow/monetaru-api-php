<?php

namespace AvtoDev\MonetaApi\Tests\References;

use AvtoDev\MonetaApi\References\PaymentCardReference;

class PaymentCardReferenceTest extends BaseReferenceTestCase
{
    protected $constants = [
        'CARDCVV2',
        'CARDEXPIRATION',
        'CARDNUMBER',
    ];

    protected function getAll()
    {
        return PaymentCardReference::getAll();
    }
}
