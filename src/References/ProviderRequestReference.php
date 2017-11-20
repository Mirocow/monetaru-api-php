<?php

namespace AvtoDev\MonetaApi\References;

class ProviderRequestReference extends AbstractReference
{
    const FIELD_PROVIDER_ID = 'providerId';

    public static function getAll()
    {
        return [
            static::FIELD_PROVIDER_ID,
        ];
    }
}
