<?php

namespace AvtoDev\MonetaApi\References;

/**
 * Class FineReference.
 *
 * Справочник полей объекта штрафа
 */
class FineReference extends AbstractReference
{
    /**
     *  Уникальный идентификатор постановления.
     */
    const FIELD_UIN = 'id';

    /**
     * Сумма штрафа.
     */
    const FIELD_AMOUNT = 'amount';

    /**
     * Название.
     */
    const FIELD_LABEL = 'label';

    /**
     * Контент штрафа.
     */
    const FIELD_CONTENT = 'content';

    /**
     * Дата постановления.
     */
    const FIELD_BILL_DATE = 'CUSTOMFIELD:BILLDATE';

    /**
     * Наименование поставщика.
     */
    const FIELD_SOI_NAME = 'CUSTOMFIELD:SOINAME';

    /**
     * ИНН получателя.
     */
    const FIELD_WIRE_USER_INN = 'WIREUSERINN';

    /**
     * КПП получателя.
     */
    const FIELD_WIRE_KPP = 'WIREKPP';

    /**
     * Номер счета получателя платежа.
     */
    const FIELD_WIRE_BANK_ACCOUNT = 'WIREBANKACCOUNT';

    /**
     * Наименование банка получателя платежа.
     */
    const FIELD_WIRE_BANK_NAME = 'WIREBANKNAME';

    /**
     * БИК.
     */
    const FIELD_WIRE_BANK_BIK = 'WIREBANKBIK';

    /**
     * Назначение платежа.
     */
    const FIELD_WIRE_PAYMENT_PURPOSE = 'WIREPAYMENTPURPOSE';

    /**
     * Наименование получателя платежа.
     */
    const FIELD_WIRE_USERNAME = 'WIREUSERNAME';

    /**
     * КБК.
     */
    const FIELD_WIRE_KBK = 'WIREKBK';

    /**
     * ОКТМО (ОКАТО).
     */
    const FIELD_WIRE_OKTMO = 'WIREOKTMO';

    /**
     * Альтернативный идентификатор плательщика.
     */
    const FIELD_WIRE_ALT_PAYER_IDENTIFIER = 'WIREALTPAYERIDENTIFIER';

    /**
     * цифровая подпись параметров данного начисления (используется при оплате).
     */
    const FIELD_SIGN = 'CUSTOMFIELD:SIGN';

    /**
     * Сумма к оплате.
     */
    const FIELD_TOTAL_AMOUNT = 'CUSTOMFIELD:TOTALAMOUNT';

    /**
     * Оплачен.
     */
    const FIELD_IS_PAID = 'CUSTOMFIELD:ISPAID';

    /**
     * размер скидки в %.
     */
    const FIELD_DISCOUNT_SIZE = 'CUSTOMFIELD:DISCOUNTSIZE';

    /**
     * дата действия скидки по оплате.
     */
    const FIELD_DISCOUNT_DATE = 'CUSTOMFIELD:DISCOUNTDATE';

    /**
     * Название атрибута в котором содержатся штрафы.
     */
    const FIELD_FINES = 'CUSTOMFIELD:105';

    /**
     * Формат даты.
     */
    const DATE_FORMAT = CommonReference::DATE_FORMAT;

    /**
     * Название шага.
     */
    const STEP_PAY = 'PAY';

    /**
     * {@inheritdoc}
     *
     * @return array|string[]
     */
    public static function getAll()
    {
        return [
            static::FIELD_UIN,
            static::FIELD_AMOUNT,
            static::FIELD_LABEL,
            static::FIELD_CONTENT,
            static::FIELD_BILL_DATE,
            static::FIELD_SOI_NAME,
            static::FIELD_WIRE_USER_INN,
            static::FIELD_WIRE_KPP,
            static::FIELD_WIRE_BANK_ACCOUNT,
            static::FIELD_WIRE_BANK_NAME,
            static::FIELD_WIRE_BANK_BIK,
            static::FIELD_WIRE_PAYMENT_PURPOSE,
            static::FIELD_WIRE_USERNAME,
            static::FIELD_WIRE_KBK,
            static::FIELD_WIRE_OKTMO,
            static::FIELD_WIRE_ALT_PAYER_IDENTIFIER,
            static::FIELD_SIGN,
            static::FIELD_TOTAL_AMOUNT,
            static::FIELD_IS_PAID,
            static::FIELD_DISCOUNT_SIZE,
            static::FIELD_DISCOUNT_DATE,
            static::DATE_FORMAT,
            static::FIELD_FINES,
            static::STEP_PAY,
        ];
    }
}
