<?php

namespace AvtoDev\MonetaApi\References;

class OperationInfoPaymentRequestReference extends AbstractReference
{
    /**
     * Уникальный идентификатор постановления.
     */
    const UIN = FIneReference::FIELD_UIN;

    /**
     * Сумма штрафа.
     */
    const AMOUNT = FIneReference::FIELD_AMOUNT;

    /**
     * Название.
     */
    const LABEL = FIneReference::FIELD_LABEL;

    /**
     * Контент штрафа.
     */
    const CONTENT = FIneReference::FIELD_CONTENT;

    /**
     * Дата постановления.
     */
    const BILL_DATE = FIneReference::FIELD_BILL_DATE;

    /**
     * Наименование поставщика.
     */
    const SOI_NAME = FIneReference::FIELD_SOI_NAME;

    /**
     * ИНН получателя.
     */
    const WIRE_USER_INN = FIneReference::FIELD_WIRE_USER_INN;

    /**
     * КПП получателя.
     */
    const WIRE_KPP = FIneReference::FIELD_WIRE_KPP;

    /**
     * Номер счета получателя платежа.
     */
    const WIRE_BANK_ACCOUNT = FIneReference::FIELD_WIRE_BANK_ACCOUNT;

    /**
     * Наименование банка получателя платежа.
     */
    const WIRE_BANK_NAME = FIneReference::FIELD_WIRE_BANK_NAME;

    /**
     * БИК.
     */
    const WIRE_BANK_BIK = FIneReference::FIELD_WIRE_BANK_BIK;

    /**
     * Назначение платежа.
     */
    const WIRE_PAYMENT_PURPOSE = FIneReference::FIELD_WIRE_PAYMENT_PURPOSE;

    /**
     * Наименование получателя платежа.
     */
    const WIRE_USERNAME = FIneReference::FIELD_WIRE_USERNAME;

    /**
     * КБК.
     */
    const WIRE_KBK = FIneReference::FIELD_WIRE_KBK;

    /**
     * ОКТМО (ОКАТО).
     */
    const WIRE_OKTMO = FIneReference::FIELD_WIRE_OKTMO;

    /**
     * Альтернативный идентификатор плательщика.
     */
    const WIRE_ALT_PAYER_IDENTIFIER = FIneReference::FIELD_WIRE_ALT_PAYER_IDENTIFIER;

    /**
     * цифровая подпись параметров данного начисления (используется при оплате).
     */
    const SIGN = FIneReference::FIELD_SIGN;

    /**
     * Сумма к оплате.
     */
    const TOTAL_AMOUNT = FIneReference::FIELD_TOTAL_AMOUNT;

    /**
     * размер скидки в %.
     */
    const DISCOUNT_SIZE = FIneReference::FIELD_DISCOUNT_SIZE;

    /**
     * дата действия скидки по оплате.
     */
    const DISCOUNT_DATE = FineReference::FIELD_DISCOUNT_DATE;

    public static function getAll()
    {
        return [
            static::UIN,
            static::AMOUNT,
            static::LABEL,
            static::CONTENT,
            static::BILL_DATE,
            static::SOI_NAME,
            static::WIRE_USER_INN,
            static::WIRE_KPP,
            static::WIRE_BANK_ACCOUNT,
            static::WIRE_BANK_NAME,
            static::WIRE_BANK_BIK,
            static::WIRE_PAYMENT_PURPOSE,
            static::WIRE_USERNAME,
            static::WIRE_KBK,
            static::WIRE_OKTMO,
            static::WIRE_ALT_PAYER_IDENTIFIER,
            static::SIGN,
            static::TOTAL_AMOUNT,
            static::DISCOUNT_SIZE,
            static::DISCOUNT_DATE,
        ];
    }
}
