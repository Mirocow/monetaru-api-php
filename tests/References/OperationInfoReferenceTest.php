<?php

namespace AvtoDev\MonetaApi\Tests\References;

use AvtoDev\MonetaApi\References\OperationInfoReference;

class OperationInfoReferenceTest extends BaseReferenceTestCase
{
    protected $constants = [
        'category',
        'clienttransaction',
        'id',
        'isreversed',
        'modified',
        'sourceaccountid',
        'sourceamount',
        'sourceamountfee',
        'sourceamounttotal',
        'sourcecurrencycode',
        'statusid',
        'targetaccountid',
        'targetalias',
        'typeid',

    ];

    protected function getAll()
    {
        return OperationInfoReference::getAll();
    }
}
