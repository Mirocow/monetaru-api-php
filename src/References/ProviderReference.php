<?php

namespace AvtoDev\MonetaApi\References;

class ProviderReference extends AbstractReference
{
    const FIELD_ID              = 'id';

    const FIELD_NAME            = 'name';

    const FIELD_SUB_PROVIDER_ID = 'subProviderId';

    public static function getAll()
    {
        return [
            static::FIELD_ID,
            static::FIELD_NAME,
            static::FIELD_SUB_PROVIDER_ID,
        ];
    }
}
