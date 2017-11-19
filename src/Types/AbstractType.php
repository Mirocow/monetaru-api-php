<?php

namespace AvtoDev\MonetaApi\Types;

use AvtoDev\MonetaApi\Traits\HasAttributes;
use AvtoDev\MonetaApi\Traits\ConvertToArray;
use AvtoDev\MonetaApi\Traits\ConvertToCarbon;
use AvtoDev\MonetaApi\Support\Contracts\Configurable;

abstract class AbstractType implements Configurable
{
    use ConvertToArray, HasAttributes, ConvertToCarbon;

    protected $assoc = [];

    public function __construct($response = null)
    {
        $this->configure($response);
    }
}
