<?php

namespace AvtoDev\MonetaApi\Traits;

use AvtoDev\MonetaApi\Exceptions\MonetaBadRequestException;

/**
 * Trait FormatPhone.
 */
trait FormatPhone
{
    /**
     * @param $phone
     *
     * @throws MonetaBadRequestException
     *
     * @return int
     */
    protected function normalizePhone($phone)
    {
        $phone = str_replace('+7', '8', $phone);
        $phone = preg_replace('/[^\d]/', '', $phone);
        if (mb_strlen($phone) !== 11) {
            throw new MonetaBadRequestException(
                'Некорректный формат поля "Контактный телефон плательщика"',
                '500.4.1.2'
            );
        }

        return (int) $phone;
    }

    /**
     * @param $phone
     *
     * @return int|string
     */
    protected function formatPhone($phone)
    {
        $phone = $this->normalizePhone($phone);
        preg_match('/^\d(\d{3})(\d{3})(\d{2})(\d{2})$/', $phone, $matches);
        $phone = '8 (' . $matches[1] . ') ' . $matches[2] . '-' . $matches[3] . '-' . $matches[4];

        return $phone;
    }
}
