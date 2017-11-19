<?php

namespace AvtoDev\MonetaApi\Tests\Traits\Mock;

use AvtoDev\MonetaApi\Traits\HasAttributes;
use AvtoDev\MonetaApi\Traits\ConvertToArray;
use AvtoDev\MonetaApi\Traits\ConvertToCarbon;

class TraitMock
{
    use ConvertToArray,ConvertToCarbon,HasAttributes;
    public $test;
    public $dateFormat;

    public function toArray()
    {
        return $this->convertToArray($this->test);
    }

    public function toCarbon()
    {
        return $this->convertToCarbon($this->test, $this->dateFormat);
    }
}
