<?php

namespace AvtoDev\MonetaApi\References;

/**
 * Class FinesRequestReference.
 *
 * Поля запроса штрафов
 */
class FinesRequestReference extends AbstractReference
{
    /**
     * Способ поиска постановления.
     */
    const SEARCH_METHOD = 'CUSTOMFIELD:200';

    /**
     * Способ поиска постановления: по УИН.
     */
    const SEARCH_METHOD_UIN = 0;

    /**
     * Способ поиска постановления: по личным данным.
     */
    const SEARCH_METHOD_PERSONAL = 1;

    /**
     * Способ поиска постановления: по альтернативному идентификатору.
     */
    const SEARCH_METHOD_ALTID = 5;

    /**
     * Номер свидетельства о регистрации ТС
     *
     * @depend static::SEARCH_METHOD_PERSONAL
     */
    const SEARCH_BY_STS = 'CUSTOMFIELD:102';

    /**
     * Номер водительского удостоверения.
     *
     * @depend static::SEARCH_METHOD_PERSONAL
     */
    const SEARCH_BY_DRIVE_LICENCE = 'CUSTOMFIELD:103';

    /**
     * Уникальный идентификатор начисления.
     *
     * @depend static::SEARCH_METHOD_UIN
     */
    const SEARCH_BY_UIN = 'CUSTOMFIELD:101';

    /**
     * Альтернативный идентификатор плательщика.
     *
     * @see    http://www.garant.ru/products/ipo/prime/doc/70510454/
     * @depend static::SEARCH_METHOD_ALTID
     */
    const SEARCH_BY_ALTID = 'CUSTOMFIELD:108';

    /**
     * Статус начислений.
     */
    const CHARGE_STATUS = 'CUSTOMFIELD:114';

    /**
     * Статус начислений: Неоплаченные.
     */
    const CHARGE_STATUS_FALSE = 'CHARGE';

    /**
     * Статус начислений: Оплаченные и неоплаченные.
     */
    const CHARGE_STATUS_BOTH = 'CHARGESTATUS';

    /**
     * Начальная дата "окна" поиска.
     *
     * @depend static::SEARCH_METHOD_PERSONAL
     */
    const DATE_FROM = 'CUSTOMFIELD:112';

    /**
     * Конечная дата "окна" поиска.
     *
     * @depend static::SEARCH_METHOD_PERSONAL
     */
    const DATE_TO = 'CUSTOMFIELD:113';

    /**
     * Формат даты.
     */
    const DATE_FORMAT = CommonReference::DATE_FORMAT;

    /**
     * {@inheritdoc}
     *
     * @return array|string[]
     */
    public static function getAll()
    {
        return [
            static::SEARCH_METHOD,
            static::SEARCH_METHOD_UIN,
            static::SEARCH_METHOD_PERSONAL,
            static::SEARCH_METHOD_ALTID,
            static::SEARCH_BY_STS,
            static::SEARCH_BY_DRIVE_LICENCE,
            static::SEARCH_BY_UIN,
            static::SEARCH_BY_ALTID,
            static::CHARGE_STATUS,
            static::CHARGE_STATUS_FALSE,
            static::CHARGE_STATUS_BOTH,
            static::DATE_FROM,
            static::DATE_TO,
            static::DATE_FORMAT,
        ];
    }
}
