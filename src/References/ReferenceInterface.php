<?php

namespace AvtoDev\MonetaApi\References;

interface ReferenceInterface
{
    /**
     * Возвращает массив всех возможных значений справочника.
     *
     * @return array|string[]
     */
    public static function getAll();

    /**
     * Проверяет - имеется ли переданное методу значение в справочнике.
     *
     * @param $value
     *
     * @return bool
     */
    public static function has($value);
}
