<?php

namespace AvtoDev\MonetaApi\Tests\Support\Mock;

use AvtoDev\MonetaApi\Support\AbstractCollection;

class MockCollection extends AbstractCollection
{
    public function push($value)
    {
        $this->stack[] = $value;
    }

    public function jsonSerialize()
    {
        return $this->stack;
    }
}
