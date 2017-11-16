<?php

namespace AvtoDev\MonetaApi\Support\Contracts;

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
