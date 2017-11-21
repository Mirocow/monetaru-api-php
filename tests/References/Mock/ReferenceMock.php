<?php

namespace AvtoDev\MonetaApi\Tests\References\Mock;

use AvtoDev\MonetaApi\References\AbstractReference;

class ReferenceMock extends AbstractReference
{
    const ONE = 1;

    const TWO = 2;

    public static function getAll()
    {
        return [
            static::ONE,
            static::TWO,
        ];
    }
}
