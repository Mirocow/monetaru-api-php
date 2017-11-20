<?php

namespace AvtoDev\MonetaApi\Tests\Traits\Mock;

use AvtoDev\MonetaApi\Traits\FormatPhone;
use AvtoDev\MonetaApi\Traits\HasAttributes;
use AvtoDev\MonetaApi\Traits\ConvertToArray;
use AvtoDev\MonetaApi\Traits\ConvertToCarbon;
use AvtoDev\MonetaApi\Traits\StackValuesDotAccessible;

class TraitMock
{
    use ConvertToArray, ConvertToCarbon, HasAttributes, StackValuesDotAccessible, FormatPhone;

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

    public function setTest($test)
    {
        $this->test = $test;
    }

    public function setDateFormat($dateFormat)
    {
        $this->dateFormat = $dateFormat;
    }

    public function setConfig($config)
    {
        $this->config = $config;
    }

    protected function getAccessorStack()
    {
        return $this->config;
    }
}
