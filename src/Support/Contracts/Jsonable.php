<?php

namespace AvtoDev\MonetaApi\Support\Contracts;

interface Jsonable
{
    /**
     * Возвращает объект ответа в виде json-строки.
     *
     * @param int $options
     *
     * @return mixed
     */
    public function toJson($options = 0);
}
