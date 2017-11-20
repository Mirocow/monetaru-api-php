<?php

namespace AvtoDev\MonetaApi\References;

class ProviderReference extends AbstractReference
{
    const FIELD_ID                = 'id';

    const FIELD_NAME              = 'name';

    const FIELD_SUB_PROVIDER_ID   = 'subProviderId';

    const FIELD_TARGET_ACCOUNT_ID = 'targetAccountId';

    public static function getAll()
    {
        return [
            static::FIELD_ID,
            static::FIELD_NAME,
            static::FIELD_SUB_PROVIDER_ID,
            static::FIELD_TARGET_ACCOUNT_ID,
        ];
    }
}
