<?php

namespace AvtoDev\MonetaApi\References;

use AvtoDev\MonetaApi\Support\Contracts\Jsonable;
use AvtoDev\MonetaApi\Support\Contracts\Arrayable;

/**
 * Class AbstractReference.
 *
 * Абстрактный класс справочника. Как правило, справочник реализует статические методы для получения данных.
 */
abstract class AbstractReference implements ReferenceInterface, Arrayable, Jsonable
{
    /**
     * {@inheritdoc}
     */
    public static function has($value)
    {
        return in_array($value, static::getAll(), true);
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        return (array) static::getAll();
    }

    /**
     * {@inheritdoc}
     */
    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }
}
