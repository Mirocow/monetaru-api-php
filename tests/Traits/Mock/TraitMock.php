<?php

namespace AvtoDev\MonetaApi\Tests\Traits\Mock;

use AvtoDev\MonetaApi\Traits\HasAttributes;
use AvtoDev\MonetaApi\Traits\ConvertToArray;
use AvtoDev\MonetaApi\Traits\ConvertToCarbon;
use AvtoDev\MonetaApi\Traits\StackValuesDotAccessible;

class TraitMock
{
    use ConvertToArray, ConvertToCarbon, HasAttributes, StackValuesDotAccessible;

    public $test;

    public $dateFormat;

    public $config;

    public function toArray()
    {
        return $this->convertToArray($this->test);
    }

    public function toCarbon()
    {
        return $this->convertToCarbon($this->test, $this->dateFormat);
    }

    protected function getAccessorStack()
    {
        return $this->config;
    }
}
