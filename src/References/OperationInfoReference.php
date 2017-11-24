<?php

namespace AvtoDev\MonetaApi\References;

class OperationInfoReference extends AbstractReference
{
    /**
     * Номер операции.
     */
    const FIELD_ID = 'id';

    /**
     * Статус операции.
     */
    const FIELD_STATUS = 'statusid';

    /**
     * Внешний (не в системе МОНЕТА.РУ) номер операции.
     */
    const FIELD_CLIENT_TRANSACTION = 'clienttransaction';

    /**
     *  Тип операции.
     */
    const FIELD_TYPE_ID = 'typeid';

    /**
     *  Категория операции.
     */
    const FIELD_CATEGORY = 'category';

    /**
     * Время последнего изменения операции.
     */
    const FIELD_MODIFIED = 'modified';

    /**
     *  Номер счета, с которого произведена операция.
     */
    const FIELD_SOURCE_ACCOUNT_ID = 'sourceaccountid';

    /**
     *  Валюта счета.
     */
    const FIELD_SOURCE_CURRENCY_CODE = 'sourcecurrencycode';

    /**
     *  Сумма по операции.
     */
    const FIELD_SOURCE_AMOUNT = 'sourceamount';

    /**
     *  Сумма комиссии.
     */
    const FIELD_SOURCE_AMOUNT_FEE = 'sourceamountfee';

    /**
     *  Общая сумма с учетом комиссии.
     */
    const FIELD_SOURCE_AMOUNT_TOTAL = 'sourceamounttotal';

    /**
     *  Корреспондентский счет.
     */
    const FIELD_TARGET_ACCOUNT_ID = 'targetaccountid';

    /**
     *  Название корреспондентского счета.
     */
    const FIELD_TARGET_ALIAS = 'targetalias';

    /**
     * true  sourceaccountid=получатель, targetaccountid=плательщик.
     * false sourceaccountid=плательщик, targetaccountid=получатель.
     */
    const FIELD_IS_REVERSED = 'isreversed';

    public static function getAll()
    {
        return [
            static::FIELD_ID,
            static::FIELD_STATUS,
            static::FIELD_CLIENT_TRANSACTION,
            static::FIELD_TYPE_ID,
            static::FIELD_CATEGORY,
            static::FIELD_MODIFIED,
            static::FIELD_SOURCE_ACCOUNT_ID,
            static::FIELD_SOURCE_CURRENCY_CODE,
            static::FIELD_SOURCE_AMOUNT,
            static::FIELD_SOURCE_AMOUNT_FEE,
            static::FIELD_SOURCE_AMOUNT_TOTAL,
            static::FIELD_TARGET_ACCOUNT_ID,
            static::FIELD_TARGET_ALIAS,
            static::FIELD_IS_REVERSED,
        ];
    }
}
