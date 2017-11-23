<?php

namespace AvtoDev\MonetaApi\Types\Requests;

use AvtoDev\MonetaApi\Clients\MonetaApi;
use AvtoDev\MonetaApi\Support\AttributeCollection;
use AvtoDev\MonetaApi\Types\Attributes\MonetaAttribute;
use AvtoDev\MonetaApi\References\InvoiceRequestReference;
use AvtoDev\MonetaApi\References\OperationInfoPaymentRequestReference;

/**
 * Class InvoiceRequest.
 * Выставление счета для оплаты. Используется для получения Payment Token.
 *
 * @see InvoiceRequestReference
 * @see OperationInfoPaymentRequestReference
 *
 * @todo: На демо Access is denied
 */
class InvoiceRequest extends AbstractRequest
{
    protected $methodName = 'InvoiceRequest';

    /**
     * {@inheritdoc}
     */
    protected $required = [
        InvoiceRequestReference::FIELD_AMOUNT,
        InvoiceRequestReference::FIELD_PAYEE,
    ];

    /**
     * Коллекция дополнительных аттрибутов.
     *
     * @see OperationInfoPaymentRequestReference
     *
     * @var AttributeCollection
     */
    protected $operationInfo;

    /**
     * {@inheritdoc}
     */
    public function __construct(MonetaApi $api)
    {
        parent::__construct($api);
        $this->operationInfo = new AttributeCollection;
    }

    /**
     * {@inheritdoc}
     *
     * @return mixed
     */
    public function prepare($response)
    {
        return $response;
    }

    /**
     * Устанавливает сумму к списанию.
     * Обязательно.
     *
     * @param $amount
     *
     * @return $this
     */
    public function setAmount($amount)
    {
        $this->attributes->push(new MonetaAttribute(InvoiceRequestReference::FIELD_AMOUNT, (float) $amount));

        return $this;
    }

    /**
     * Устанавливает счет получателя.
     * Обязательно.
     *
     * @param $number
     *
     * @return $this
     */
    public function setDestinationAccount($number)
    {
        $this->attributes->push(new MonetaAttribute(InvoiceRequestReference::FIELD_PAYEE, (string) trim($number)));

        return $this;
    }

    /**
     * Запроить payment token.
     *
     * @return $this
     */
    public function requestPaymentToken()
    {
        $this->operationInfo->push(new MonetaAttribute(OperationInfoPaymentRequestReference::PAYMENT_TOKEN, 'request'));

        return $this;
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
                InvoiceRequestReference::FIELD_OPERATION_INFO => [
                    'attribute' => $operationInfo,
                ],
            ],
            $attributes
        );
    }
}
