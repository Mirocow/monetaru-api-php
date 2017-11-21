<?php

namespace AvtoDev\MonetaApi\References;

class CommonReference extends AbstractReference
{
    /**
     * Формат даты.
     */
    const DATE_FORMAT = 'Y-m-d';

    const PROVIDER_ID = 'providerId';

    /**
     * {@inheritdoc}
     */
    public static function getAll()
    {
        return [
            static::DATE_FORMAT,
            static::PROVIDER_ID,
        ];
    }
}
