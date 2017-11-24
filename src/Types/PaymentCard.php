<?php

namespace AvtoDev\MonetaApi\Types;

use Carbon\Carbon;
use AvtoDev\MonetaApi\References\PaymentCardReference;
use AvtoDev\MonetaApi\Types\Attributes\MonetaAttribute;
use AvtoDev\MonetaApi\Exceptions\MonetaBadRequestException;

/**
 * Class PaymentCard.
 *
 * Пластиковая карта
 *
 * @see PaymentCardReference
 */
class PaymentCard extends AbstractType
{
    /**
     * Поля обязательные к заполнению.
     *
     * @var array
     */
    protected $required = [
        PaymentCardReference::CARD_NUMBER,
        PaymentCardReference::CARD_EXPIRATION,
        PaymentCardReference::CARD_CVV2,
    ];

    /**
     * {@inheritdoc}
     */
    public function configure($content)
    {
        $config = $this->convertToArray($content);
        foreach ((array) $config as $key => $value) {
            switch ($key) {
                case PaymentCardReference::CARD_NUMBER:
                    $this->setNumber($value);
                    break;
                case PaymentCardReference::CARD_EXPIRATION:
                    $this->setExpirationDate($value);
                    break;
                case PaymentCardReference::CARD_CVV2:
                    $this->setCVV2($value);
                    break;
            }
        }
    }

    /**
     * Устанавливает номер карты.
     *
     * @param string $number
     *
     * @throws MonetaBadRequestException
     *
     * @return $this
     */
    public function setNumber($number)
    {
        if ($this->isValidNumber($number)) {
            $this->attributes->push(
                new MonetaAttribute(
                    PaymentCardReference::CARD_NUMBER,
                    $this->normalizeNumber($number)
                )
            );
        } else {
            throw new MonetaBadRequestException(
                'Некорректный формат поля "' . PaymentCardReference::CARD_NUMBER . '"',
                '500.4.1.2'
            );
        }

        return $this;
    }

    /**
     * Устанавливает срок действия карты.
     *
     * @param Carbon|\DateTime|int|string $expiration Дата окончания действия
     *
     * @throws MonetaBadRequestException
     *
     * @return $this
     */
    public function setExpirationDate($expiration)
    {
        $expiration = $this->convertToCarbon($expiration);
        if ($expiration->diffInMonths(Carbon::now(), false) >= 0) {
            throw new MonetaBadRequestException('Срок действия карты истек', '500');
        }
        $this->attributes->push(
            new MonetaAttribute(
                PaymentCardReference::CARD_EXPIRATION,
                $expiration->format(PaymentCardReference::EXPIRATION_DATA_FORMAT)
            )
        );

        return $this;
    }

    /**
     * Устанавливает защитный код карты карты.
     *
     * @param string $cvv2 cvv2 код
     *
     * @throws MonetaBadRequestException
     *
     * @return $this
     */
    public function setCVV2($cvv2)
    {
        if (is_numeric($cvv2) && mb_strlen($cvv2) > 2 && mb_strlen($cvv2) < 5) {
            $this->attributes->push(
                new MonetaAttribute(
                    PaymentCardReference::CARD_CVV2,
                    (string) $cvv2
                )
            );
        } else {
            throw new MonetaBadRequestException(
                'Некорректный формат поля "' . PaymentCardReference::CARD_CVV2 . '"',
                '500.4.1.2'
            );
        }

        return $this;
    }

    /**
     * Проверка валидности номера карты.
     *
     * @param $number
     *
     * @return bool
     */
    public function isValidNumber($number)
    {
        $number = (string) $this->normalizeNumber($number);

        return (mb_strlen($number) > 11 && mb_strlen($number) < 20);
    }

    /**
     * Нормализация номера карты.
     *
     * @param $number
     *
     * @return string
     */
    public function normalizeNumber($number)
    {
        return preg_replace('/[^\d]/', '', $number);
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        $attributes = [];
        foreach ($this->attributes as $attribute) {
            $attributes[] = $attribute->toAttribute('key');
        }

        return $attributes;
    }

    /**
     * {@inheritdoc}
     */
    protected function getCollection()
    {
        return $this->attributes;
    }

    /**
     * {@inheritdoc}
     */
    protected function getRequiredReference()
    {
        return $this->required;
    }
}
