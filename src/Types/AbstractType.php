<?php

namespace AvtoDev\MonetaApi\Types;

use Carbon\Carbon;
use AvtoDev\MonetaApi\Traits\HasAttributes;
use AvtoDev\MonetaApi\Traits\ConvertToArray;
use AvtoDev\MonetaApi\Traits\ConvertToCarbon;
use AvtoDev\MonetaApi\Support\Contracts\Configurable;

abstract class AbstractType implements Configurable
{
    use ConvertToArray, HasAttributes, ConvertToCarbon;

    protected $assoc = [];

    protected $casts = [];

    public function __construct($response = null)
    {
        $this->configure($response);
    }

    public function cast($name, $value)
    {
        if (isset($this->casts[$name])) {
            switch ($this->casts[$name]) {
                case 'date':
                    $value = Carbon::createFromFormat('Y-m-d', $value);
                    break;
                case 'float':
                    $value = (float) $value;
                    break;
                case 'bool':
                    $value = (bool) $value;
                    break;
            }
        }

        return $value;
    }
}
