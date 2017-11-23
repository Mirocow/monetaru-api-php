<?php

namespace AvtoDev\MonetaApi\References;

/**
 * Class CommonReference.
 *
 * Справочник общих значений
 */
class CommonReference extends AbstractReference
{
    /**
     * Формат даты.
     */
    const DATE_FORMAT = 'Y-m-d';

    /**
     * Название поля id провайдера.
     */
    const PROVIDER_ID = 'providerId';

    /**
     * {@inheritdoc}
     *
     * @return array|string[]
     */
    public static function getAll()
    {
        return [
            static::DATE_FORMAT,
            static::PROVIDER_ID,
        ];
    }
}
