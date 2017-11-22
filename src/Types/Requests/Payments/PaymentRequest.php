<?php

namespace AvtoDev\MonetaApi\Types\Requests\Payments;

use AvtoDev\MonetaApi\Types\Fine;
use AvtoDev\MonetaApi\Types\Payment;
use AvtoDev\MonetaApi\Clients\MonetaApi;
use AvtoDev\MonetaApi\Support\AttributeCollection;
use AvtoDev\MonetaApi\Types\Attributes\MonetaAttribute;
use AvtoDev\MonetaApi\References\PaymentRequestReference;
use AvtoDev\MonetaApi\Exceptions\MonetaBadRequestException;
use AvtoDev\MonetaApi\References\OperationInfoPaymentRequestReference;

class PaymentRequest extends AbstractPaymentRequest
{
    protected $methodName = 'PaymentRequest';

    protected $fio;

    /**
     * @var AttributeCollection
     */
    protected $operationInfo;

    protected $required              = [
        PaymentRequestReference::FIELD_PAYER,
        PaymentRequestReference::FIELD_PAYEE,
        PaymentRequestReference::FIELD_AMOUNT,
        PaymentRequestReference::FIELD_IS_PAYER_AMOUNT,
    ];

    protected $operationInfoRequired = [
        OperationInfoPaymentRequestReference::PAYER_PHONE,
        OperationInfoPaymentRequestReference::PAYER_FIO,
    ];

    /**
     * PaymentRequest constructor.
     *
     * @param MonetaApi $api
     */
    public function __construct(
        MonetaApi $api

    ) {
        parent::__construct($api);
        $this->operationInfo = new AttributeCollection;
    }

    /**
     * {@inheritdoc}
     *
     * @return Payment
     */
    public function exec()
    {
        return parent::exec();
    }

    /**
     * {@inheritdoc}
     *
     * @return Payment
     */
    public function prepare($response)
    {
        return new Payment($response->PaymentResponse);
    }

    /**
     * Устанавливает номер счета плательщика.
     * Обязателен.
     *
     * @param string $accountNumber
     *
     * @return $this
     */
    public function setAccountNumber($accountNumber)
    {
        $this->attributes->push(new MonetaAttribute(PaymentRequestReference::FIELD_PAYER,
            (string) trim($accountNumber)));

        return $this;
    }

    public function setPaymentPassword($password)
    {
        if (! empty($password)) {
            $this->attributes->push(new MonetaAttribute(PaymentRequestReference::FIELD_PAYMENT_PASSWORD,
                (string) trim($password)));
        }

        return $this;
    }

    public function setIsPayerAmount($isPayerAmount = true)
    {
        $this->attributes->push(new MonetaAttribute(PaymentRequestReference::FIELD_IS_PAYER_AMOUNT, (bool)
        $isPayerAmount));

        return $this;
    }

    public function setDestinationAccount($accountNumber)
    {
        $this->attributes->push(new MonetaAttribute(PaymentRequestReference::FIELD_PAYEE,
            (string) trim($accountNumber)));

        return $this;
    }

    /**
     * Устанавливает ФИО плательщика.
     * Обязателен.
     *
     * @param string $fio
     *
     * @return $this
     */
    public function setPayerFio($fio)
    {
        $this->operationInfo->push(new MonetaAttribute(OperationInfoPaymentRequestReference::PAYER_FIO,
            (string) trim($fio)));

        return $this;
    }

    /**
     * Устанавливает номер телефона плательщика.
     * Обязателен.
     *
     * @param string $phone
     *
     * @return $this
     */
    public function setPayerPhone($phone)
    {
        $this->operationInfo->push(new MonetaAttribute(OperationInfoPaymentRequestReference::PAYER_PHONE,
            $this->formatPhone($phone)));

        return $this;
    }

    public function setFine(Fine $fine)
    {
        $this->setAmount($fine->getAmount());

        foreach ($fine->getOperationInfo() as $attribute) {
            $this->operationInfo->push($attribute);
        }

        $this->operationInfo->push(
            new MonetaAttribute(OperationInfoPaymentRequestReference::FIELD_UIN, (string) $fine->getId())
        );

        $this->operationInfo->push(new MonetaAttribute(OperationInfoPaymentRequestReference::SUB_PROVIDER_ID,
            (string) $this->api->getConfigValue('accounts.provider.sub_id')));

        return $this;
    }

    public function setAmount($amount)
    {
        $this->attributes->push(new MonetaAttribute(PaymentRequestReference::FIELD_AMOUNT, (float) $amount));

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function checkRequired()
    {
        parent::checkRequired();
        if (! $this->operationInfo->isEmpty()) {
            foreach ($this->operationInfoRequired as $attribute) {
                if (! $this->operationInfo->hasByType($attribute)) {
                    throw new MonetaBadRequestException("Не заполнен обязательный атрибут: $attribute", '500.1');
                }
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function createBody()
    {
        $attributes = [];
        foreach ($this->attributes as $attribute) {
            $attributes[$attribute->getName()] = $attribute->getValue();
        }

        $operationInfo = [];
        foreach ($this->operationInfo as $attribute) {
            $operationInfo[] = $attribute->toAttribute('key');
        }

        return array_merge(
            [
                'version'                                     => $this->version,
                PaymentRequestReference::FIELD_OPERATION_INFO => [
                    'attribute' => $operationInfo,
                ],
            ],
            $attributes
        );
    }
}
