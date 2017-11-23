<?php

namespace AvtoDev\MonetaApi\Support\Contracts;

/**
 * Interface Arrayable.
 *
 * Обязывает класс быть пригодным к преобразованию в массив
 */
interface Arrayable
{
    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray();
}
