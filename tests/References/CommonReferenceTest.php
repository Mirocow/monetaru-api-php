<?php

namespace AvtoDev\MonetaApi\Tests\References;

use AvtoDev\MonetaApi\References\CommonReference;

class CommonReferenceTest extends BaseReferenceTestCase
{
    protected $constants = ['Y-m-d', 'providerId'];

    public function getAll()
    {
        return CommonReference::getAll();
    }
}
