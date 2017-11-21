<?php

namespace AvtoDev\MonetaApi\Tests\References;

use AvtoDev\MonetaApi\References\ProviderReference;

class ProviderReferenceTest extends BaseReferenceTestCase
{
    protected $constants = [
        'id',
        'name',
        'subProviderId',
        'targetAccountId',
    ];

    protected function getAll()
    {
        return ProviderReference::getAll();
    }
}
