<?php

namespace AvtoDev\MonetaApi\Tests\References;

use AvtoDev\MonetaApi\References\OperationInfoPaymentRequestReference;

class OperationInfoPaymentRequestReferenceTest extends BaseReferenceTestCase
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
        'CUSTOMFIELD:DISCOUNTSIZE',
        'CUSTOMFIELD:DISCOUNTDATE',
        'CUSTOMFIELD:105',
        'WIREPAYER',
        'CUSTOMFIELD:PHONE',
        'PAYMENTTOKEN',
    ];

    protected function getAll()
    {
        return OperationInfoPaymentRequestReference::getAll();
    }
}
