<?php

namespace AvtoDev\MonetaApi\References;

/**
 * Class PaymentCardReference.
 *
 * Справочник полей карты. Все аттрибуты обязательны
 */
class PaymentCardReference extends AbstractReference
{
    /**
     * Номер карты.
     */
    const CARD_NUMBER = 'CARDNUMBER';

    /**
     * Срок действия карты.
     *
     * @see static::EXPIRATION_DATA_FORMAT
     */
    const CARD_EXPIRATION = 'CARDEXPIRATION';

    /**
     * Код защиты карты.
     */
    const CARD_CVV2 = 'CARDCVV2';

    /**
     * Формат даты срока действия карты.
     */
    const EXPIRATION_DATA_FORMAT = 'm/Y';

    /**
     * Ид плательщика при установке платежа картой.
     */
    const CARD_PAYER_ID = '159';

    /**
     * {@inheritdoc}
     *
     * @return array|string[]
     */
    public static function getAll()
    {
        return [
            static::CARD_NUMBER,
            static::CARD_EXPIRATION,
            static::CARD_CVV2,
        ];
    }
}
