<?php

namespace AvtoDev\MonetaApi\References;

/**
 * Class PaymentRequestReference.
 *
 * Справочник полей запроса перевода средств
 */
class PaymentRequestReference extends AbstractReference
{
    /**
     * Название поля транзакции.
     */
    const TRANSACTION_FIELD_TRANSACTIONAL = 'transactional';

    /**
     * Название флага выхода при ошибке.
     */
    const TRANSACTION_FIELD_EXIT_ON_FAILURE = 'exitOnFailure';

    /**
     * Набор операций, которые необходимо выполнить в одном пакете.
     * Операции выполняются в том порядке, в котором они переданы в запросе.
     */
    const TRANSACTION_COLLECTION = 'transaction';

    /**
     * Номер счета плательщика.
     */
    const FIELD_PAYER = 'payer';

    /**
     *  Номер счета получателя.
     */
    const FIELD_PAYEE = 'payee';

    /**
     * Сумма операции.
     */
    const FIELD_AMOUNT = 'amount';

    /**
     * Если пользователь имеет доступ как к счету плательщика, так и счету получателя, то флаг isPayerAmount
     * обязателен.
     * •   Если флаг isPayerAmount установлен (true), то amount используется как сумма к списанию (в валюте
     * плательщика).
     * •   Если флаг isPayerAmount сброшен (false), то amount используется как сумма к зачислению (в валюте
     * получателя).
     */
    const FIELD_IS_PAYER_AMOUNT = 'isPayerAmount';

    /**
     * Платежный пароль счета плательщика.
     */
    const FIELD_PAYMENT_PASSWORD = 'paymentPassword';

    /**
     * Внешний номер операции.
     */
    const FIELD_CLIENT_TRANSACTION = 'clientTransaction';

    /**
     * Описание операции.
     */
    const FIELD_DESCRIPTION = 'description';

    /**
     * Набор полей, которые необходимо сохранить в качестве атрибутов операции. (Сюда передаюстся все атрибуты штрафа
     * кноме isPaid)
     * Значения дат в формате dd.MM.yyyy HH:mm:ss.
     */
    const FIELD_OPERATION_INFO = 'operationInfo';

    /**
     * Персональные данные пользователя.
     */
    const FIELD_PERSONAL_INFORMATION = 'personalInformation';

    /**
     * Ид плательщика при установке платежа картой.
     */
    const CARD_PAYER_ID = PaymentCardReference::CARD_PAYER_ID;

    /**
     * Номер родительской операции, операции НКО, которой были списаны денежные средства.
     */
    const FIELD_PARENT_ID = 'PARENTID';

    /**
     * Сумма комиссии, удержанная с клиента.
     */
    const FIELD_COMMISSION = 'SOURCETARIFFMULTIPLIER';

    /**
     * {@inheritdoc}
     *
     * @return array|string[]
     */
    public static function getAll()
    {
        return [
            static::TRANSACTION_FIELD_TRANSACTIONAL,
            static::TRANSACTION_FIELD_EXIT_ON_FAILURE,
            static::TRANSACTION_COLLECTION,
            static::FIELD_PAYER,
            static::FIELD_PAYEE,
            static::FIELD_AMOUNT,
            static::FIELD_IS_PAYER_AMOUNT,
            static::FIELD_PAYMENT_PASSWORD,
            static::FIELD_CLIENT_TRANSACTION,
            static::FIELD_DESCRIPTION,
            static::FIELD_OPERATION_INFO,
            static::FIELD_PERSONAL_INFORMATION,
            static::FIELD_PARENT_ID,
            static::FIELD_COMMISSION,
        ];
    }
}
