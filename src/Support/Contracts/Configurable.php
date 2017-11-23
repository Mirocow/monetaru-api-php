<?php

namespace AvtoDev\MonetaApi\Support\Contracts;

/**
 * Interface Configurable.
 *
 * Обзяывает класс иметь единую точку входа для установки значений
 */
interface Configurable
{
    /**
     * Метод конфигурации объекта.
     *
     * @param array|object|string|null $content
     *
     * @return void
     */
    public function configure($content);
}
