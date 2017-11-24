<?php

namespace AvtoDev\MonetaApi\Tests\Types\Mock;

use AvtoDev\MonetaApi\Types\AbstractType;
use AvtoDev\MonetaApi\Types\Attributes\MonetaAttribute;

class AbstractTypeMock extends AbstractType
{
    public function configure($content)
    {
        $config = $this->convertToArray($content);
        foreach ($config as $key => $value) {
            $this->attributes->push(new MonetaAttribute($key, $value));
        }
    }
}
