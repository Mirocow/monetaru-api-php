<?php

namespace AvtoDev\MonetaApi\References;

/**
 * Class ProviderReference.
 *
 * Справочник полей провайдера
 */
class ProviderReference extends AbstractReference
{
    /**
     * Id получателя штрафов.
     */
    const FIELD_ID = 'id';

    /**
     * Название получателя штрафов (ГИБДД).
     */
    const FIELD_NAME = 'name';

    /**
     * subProviderId (необходим для идентификации счета в системе МОНЕТА.РУ).
     */
    const FIELD_SUB_PROVIDER_ID = 'subProviderId';

    /**
     * Номер счета в системе МОНЕТА.РУ.
     */
    const FIELD_TARGET_ACCOUNT_ID = 'targetAccountId';

    /**
     * {@inheritdoc}
     *
     * @return array|string[]
     */
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
