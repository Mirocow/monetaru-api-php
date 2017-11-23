<?php

namespace AvtoDev\MonetaApi\Support\Contracts;

/**
 * Interface Collection.
 */
interface Collection extends \Iterator, \Countable
{
    /**
     * Очищает коллекцию.
     *
     * @return void
     */
    public function clear();

    /**
     * Возвращает копию текущей коллекции.
     *
     * @return self
     */
    public function copy();

    /**
     * Проверяет пустая ли коллекция.
     *
     * @return bool
     */
    public function isEmpty();

    /**
     * Конвертирует в массив.
     *
     * @return array
     */
    public function toArray();
}
