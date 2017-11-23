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

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        return [$this->name => $this->value];
    }

    /**
     * Преобразовывает аттрибут в структуру пригодную для передачи в МОНЕТА.РУ.
     *
     * @param string $keyName
     * @param string $valueName
     *
     * @return array
     */
    public function toAttribute($keyName, $valueName = 'value')
    {
        return [$keyName => $this->name, $valueName => $this->value];
    }

    /**
     * {@inheritdoc}
     */
    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }

    /**
     * Получить наименование(тип) аттрибута.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Получить значение аттрибута.
     *
     * @return bool|int|string
     */
    public function getValue()
    {
        return $this->value;
    }
}
