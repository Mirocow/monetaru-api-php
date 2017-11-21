<?php

namespace AvtoDev\MonetaApi\Tests\References;

use AvtoDev\MonetaApi\References\ProviderRequestReference;

class ProviderRequestReferenceTest extends BaseReferenceTestCase
{
    protected $constants = [
        'providerId',
    ];

    protected function getAll()
    {
        return ProviderRequestReference::getAll();
    }
}
