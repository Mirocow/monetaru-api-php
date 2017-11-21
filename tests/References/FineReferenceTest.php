<?php

namespace AvtoDev\MonetaApi\Tests\References;

use AvtoDev\MonetaApi\References\FineReference;

class FineReferenceTest extends BaseReferenceTestCase
{
    protected $constants = [
        'id',
        'amount',
        'label',
        'content',
        'CUSTOMFIELD:BILLDATE',
        'CUSTOMFIELD:SOINAME',
        'WIREUSERINN',
        'WIREKPP',
        'WIREBANKACCOUNT',
        'WIREBANKNAME',
        'WIREBANKBIK',
        'WIREPAYMENTPURPOSE',
        'WIREUSERNAME',
        'WIREKBK',
        'WIREOKTMO',
        'WIREALTPAYERIDENTIFIER',
        'CUSTOMFIELD:SIGN',
        'CUSTOMFIELD:TOTALAMOUNT',
        'CUSTOMFIELD:ISPAID',
        'CUSTOMFIELD:DISCOUNTSIZE',
        'CUSTOMFIELD:DISCOUNTDATE',
        'Y-m-d',
    ];

    protected function getAll()
    {
        return FineReference::getAll();
    }
}
