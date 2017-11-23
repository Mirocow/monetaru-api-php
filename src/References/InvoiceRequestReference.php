<?php

namespace AvtoDev\MonetaApi\References;

/**
 * Class InvoiceRequestReference.
 *
 * Справочник запроса счета (используется для получения токена для реккаринговых платежей)
 */
class InvoiceRequestReference extends AbstractReference
{
    /**
     * Счет получателя платежа.
     */
    const FIELD_PAYEE = PaymentRequestReference::FIELD_PAYEE;

    /**
     * Сумма к списанию.
     */
    const FIELD_AMOUNT = PaymentRequestReference::FIELD_AMOUNT;

    /**
     * Аттрибуты для дополнительной информации о платеже.
     */
    const FIELD_OPERATION_INFO = PaymentRequestReference::FIELD_OPERATION_INFO;

    /**
     * {@inheritdoc}
     *
     * @return array|string[]
     */
    public static function getAll()
    {
        return [
            static::FIELD_PAYEE,
            static::FIELD_AMOUNT,
            static::FIELD_OPERATION_INFO,
        ];
    }
}
