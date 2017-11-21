<?php

namespace AvtoDev\MonetaApi\Types\Requests;

use AvtoDev\MonetaApi\MonetaApi;
use AvtoDev\MonetaApi\Types\Fine;
use AvtoDev\MonetaApi\Support\AttributeCollection;
use AvtoDev\MonetaApi\Types\Attributes\MonetaAttribute;
use AvtoDev\MonetaApi\References\PaymentRequestReference;
use AvtoDev\MonetaApi\Exceptions\MonetaBadRequestException;
use AvtoDev\MonetaApi\References\OperationInfoPaymentRequestReference;

class PaymentRequest extends AbstractRequest
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

    public function __construct(
        MonetaApi $api,
        Fine $fine
    ) {
        parent::__construct($api);
        $this->operationInfo = new AttributeCollection;

        $this->setAccountNumber($api->getConfigValue('accounts.fines_account'));
        $this->attributes->push(
            new MonetaAttribute(
                PaymentRequestReference::FIELD_PAYEE,
                $this->api->getConfigValue('accounts.provider.id')
            )
        );
        $this->attributes->push(new MonetaAttribute(PaymentRequestReference::FIELD_IS_PAYER_AMOUNT, false));
        $this->buildFromFine($fine);
    }

    public function prepare($response)
    {
        // @todo: Implement prepare() method.
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
        $this->attributes->push(new MonetaAttribute(PaymentRequestReference::FIELD_PAYER, trim($accountNumber)));

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
        $this->operationInfo->push(new MonetaAttribute(OperationInfoPaymentRequestReference::PAYER_FIO, trim($fio)));

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

    /**
     * {@inheritdoc}
     */
    protected function checkRequired()
    {
        parent::checkRequired();
        foreach ($this->operationInfoRequired as $attribute) {
            if (! $this->operationInfo->hasByType($attribute)) {
                throw new MonetaBadRequestException("Не заполнен обязательный атрибут: $attribute", '500.1');
            }
        }
    }

    protected function buildFromFine(Fine $fine)
    {
        $this->attributes->push(new MonetaAttribute(PaymentRequestReference::FIELD_AMOUNT, $fine->getTotalAmount()));

        foreach ($fine->getOperationInfo() as $attribute) {
            $this->operationInfo->push($attribute);
        }

        $this->operationInfo->push(
            new MonetaAttribute(OperationInfoPaymentRequestReference::FIELD_UIN, $fine->getId())
        );

        $this->operationInfo->push(new MonetaAttribute(OperationInfoPaymentRequestReference::SUB_PROVIDER_ID, '1'));
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
