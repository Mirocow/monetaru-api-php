<?php

namespace AvtoDev\MonetaApi\Types\Attributes;

use AvtoDev\MonetaApi\Support\Contracts\Jsonable;
use AvtoDev\MonetaApi\Support\Contracts\Arrayable;

abstract class AbstractAttribute implements Arrayable, Jsonable
{
    protected $name;

    protected $value;

    /**
     * AbstractAttribute constructor.
     *
     * @param string          $name
     * @param bool|int|string $value
     */
    public function __construct($name, $value)
    {
        $this->name  = $name;
        $this->value = $value;
    }

    public function toArray()
    {
        return [$this->name => $this->value];
    }

    public function toAttribute($keyName, $valueName = 'value')
    {
        return [$keyName => $this->name, $valueName => $this->value];
    }

    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }

    public function getName()
    {
        return $this->name;
    }

    /**
     * @return bool|int|string
     */
    public function getValue()
    {
        return $this->value;
    }
}
