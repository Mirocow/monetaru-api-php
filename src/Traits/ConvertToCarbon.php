<?php

namespace AvtoDev\MonetaApi\Traits;

use DateTime;
use Carbon\Carbon;

/**
 * Trait ConvertToCarbon.
 *
 * Трейт, реализующий метод конвертации в Carbon-объект.
 */
trait ConvertToCarbon
{
    /**
     * Преобразует полученное значение в объект Carbon.
     *
     * @param Carbon|DateTime|int|string $value
     * @param string|null                $format
     *
     * @return Carbon|null
     */
    protected function convertToCarbon($value, $format = null)
    {
        if ($value instanceof Carbon) {
            return $value;
        } elseif ($value instanceof DateTime) {
            return Carbon::instance($value);
        } elseif (is_int($value)) {
            return Carbon::createFromTimestamp($value);
        } elseif ($format !== null && is_string($value)) {
            return Carbon::createFromFormat($format, $value);
        } elseif (is_string($value)) {
            return Carbon::parse($value);
        }
    }
}
