<?php

namespace AvtoDev\MonetaApi\Types\Requests\Payments;

use AvtoDev\MonetaApi\Types\Requests\AbstractRequest;

abstract class AbstractPaymentRequest extends AbstractRequest
{
    /**
     * Фио плательщика.
     *
     * @var string
     */
    protected $fio;

    /**
     * Телефон плательщика.
     *
     * @var string
     */
    protected $phone;

    /**
     * Устанавливает ФИО плательщика.
     * Обязателен для оплаты штрафа.
     *
     * @param string $fio
     *
     * @return $this
     */
    public function setPayerFio($fio)
    {
        $this->fio = (string) trim($fio);

        return $this;
    }

    /**
     * Устанавливает номер телефона плательщика.
     * Если задан, пользователю отправится уведомление.
     * Обязателен для оплаты штрафа.
     *
     * @param string $phone
     *
     * @return $this
     */
    public function setPayerPhone($phone)
    {
        $this->phone = $this->formatPhone($phone);

        return $this;
    }
}
