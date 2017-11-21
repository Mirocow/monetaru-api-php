<?php

namespace AvtoDev\MonetaApi\Types;

use AvtoDev\MonetaApi\Traits\ConvertToArray;
use AvtoDev\MonetaApi\Traits\ConvertToCarbon;
use AvtoDev\MonetaApi\Support\AttributeCollection;
use AvtoDev\MonetaApi\Support\Contracts\Configurable;

abstract class AbstractType implements Configurable
{
    use ConvertToArray, ConvertToCarbon;

    protected $assoc = [];

    /**
     * @var AttributeCollection
     */
    protected $attributes;

    public function __construct($response = null)
    {
        $this->attributes = new AttributeCollection;
        $this->configure($response);
    }
}
