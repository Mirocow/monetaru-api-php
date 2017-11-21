<?php

namespace AvtoDev\MonetaApi\Tests\References;

use AvtoDev\MonetaApi\References\FinesRequestReference;

class FinesRequestReferenceTest extends BaseReferenceTestCase
{
    protected $constants = [
        'CUSTOMFIELD:200',
        0,
        1,
        5,
        'CUSTOMFIELD:102',
        'CUSTOMFIELD:103',
        'CUSTOMFIELD:101',
        'CUSTOMFIELD:108',
        'CUSTOMFIELD:114',
        'CHARGE',
        'CHARGESTATUS',
        'CUSTOMFIELD:112',
        'CUSTOMFIELD:113',
        'Y-m-d',
    ];

    protected function getAll()
    {
        return FinesRequestReference::getAll();
    }
}
