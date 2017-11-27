<?php

namespace AvtoDev\MonetaApi\Types\Requests\Payments;

use AvtoDev\MonetaApi\Types\Invoice;
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
 */
class InvoiceRequest extends AbstractPaymentRequest
{
    /**
     * {@inheritdoc}
     */
    protected $methodName = 'InvoiceRequest';

    /**
     * {@inheritdoc}
     */
    protected $responseName = 'InvoiceResponse';

    /**
     * {@inheritdoc}
     */
    protected $required = [
        InvoiceRequestReference::FIELD_AMOUNT,
        InvoiceRequestReference::FIELD_PAYEE,
        InvoiceRequestReference::FIELD_CLIENT_TRANSACTION_ID,
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
     * @return Invoice
     */
    public function exec()
    {
        return parent::exec();
    }

    /**
     * {@inheritdoc}
     *
     * @return mixed
     */
    public function prepare($response)
    {
        return new Invoice($response);
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
        $this->setAccountNumber($this->api->getConfigValue('accounts.payer_card'));

        return $this;
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
        $this->attributes->push(new MonetaAttribute(
            InvoiceRequestReference::FIELD_PAYER,
            (string) trim($accountNumber)
        ));

        return $this;
    }

    /**
     * @param string $id
     *
     * @return $this
     */
    public function setClientTransactionId($id)
    {
        $this->attributes->push(new MonetaAttribute(
            InvoiceRequestReference::FIELD_CLIENT_TRANSACTION_ID,
            (string) trim($id)
        ));

        return $this;
    }

    /**
     * Возвращает копию аттрибутов.
     *
     * @return AttributeCollection
     */
    public function getOperationInfo()
    {
        return $this->operationInfo->copy();
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
                InvoiceRequestReference::FIELD_OPERATION_INFO => [
                    'attribute' => $operationInfo,
                ],
            ],
            $attributes
        );
    }
}
