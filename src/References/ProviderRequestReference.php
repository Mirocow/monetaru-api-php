<?php

namespace AvtoDev\MonetaApi\References;

/**
 * Class ProviderRequestReference.
 *
 * Поля запроса провайдера
 */
class ProviderRequestReference extends AbstractReference
{
    /**
     * Id провайдера в системе МОНЕТА.РУ.
     */
    const FIELD_PROVIDER_ID = CommonReference::PROVIDER_ID;

    /**
     * {@inheritdoc}
     *
     * @return array|string[]
     */
    public static function getAll()
    {
        return [
            static::FIELD_PROVIDER_ID,
        ];
    }
}
