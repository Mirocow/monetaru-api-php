<?php

namespace AvtoDev\MonetaApi\Types\Requests;

use AvtoDev\MonetaApi\Types\Fine;
use AvtoDev\MonetaApi\HttpClientInterface;
use AvtoDev\MonetaApi\Types\Attributes\MonetaAttribute;
use AvtoDev\MonetaApi\References\PaymentRequestReference;
use AvtoDev\MonetaApi\References\OperationInfoPaymentRequestReference;

class PaymentRequest extends AbstractRequest
{
    protected $methodName = 'PaymentRequest';

    protected $fio;

    protected $required   = [
        PaymentRequestReference::FIELD_PAYER,
        PaymentRequestReference::FIELD_PAYEE,
        PaymentRequestReference::FIELD_AMOUNT,
        PaymentRequestReference::FIELD_IS_PAYER_AMOUNT,
    ];

    /**
     * @var Fine
     */
    protected $fine;

    public function __construct(
        HttpClientInterface $httpClient,
        $header,
        $providerId,
        Fine $fine
    ) {
        parent::__construct($httpClient, $header, $providerId);
        $this->fine = $fine;
        $this->pushAttribute(new MonetaAttribute(PaymentRequestReference::FIELD_PAYEE, $providerId));
        $this->pushAttribute(new MonetaAttribute(PaymentRequestReference::FIELD_AMOUNT, $fine->getTotalAmount()));
        $this->pushAttribute(new MonetaAttribute(PaymentRequestReference::FIELD_IS_PAYER_AMOUNT, false));
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
        $this->pushAttribute(new MonetaAttribute(PaymentRequestReference::FIELD_PAYER, trim($accountNumber)));

        return $this;
    }

    public function setPayerFio($fio)
    {
        $this->fine->pushAttribute(new MonetaAttribute(OperationInfoPaymentRequestReference::PAYER_FIO, trim($fio)));

        return $this;
    }

    public function setPayerPhone($phone)
    {
        $this->fine->pushAttribute(new MonetaAttribute(OperationInfoPaymentRequestReference::PAYER_PHONE,
            trim($phone)));

        return $this;
    }

    protected function createBody()
    {
        $attributes = [];
        foreach ($this->attributes() as $attribute) {
            $attributes[$attribute->getName()] = $attribute->getValue();
        }

        $operationInfoAttributes = $this->fine->getOperationInfo();

        $operationInfo = [];
        /**
         * @var MonetaAttribute
         */
        foreach ($operationInfoAttributes as $attribute) {
            $operationInfo[] = $attribute->toAttribute('key');
        }

        $uinAttribute    = new MonetaAttribute(OperationInfoPaymentRequestReference::FIELD_UIN, $this->fine->getId());
        $operationInfo[] = $uinAttribute->toAttribute('key');

        $subProviderId   = new MonetaAttribute(OperationInfoPaymentRequestReference::SUB_PROVIDER_ID, '1');
        $operationInfo[] = $subProviderId->toAttribute('key');

        return array_merge([
            'version'                                     => $this->version,
            PaymentRequestReference::FIELD_OPERATION_INFO => [
                'attribute' => $operationInfo,
            ],
        ], $attributes);
    }
}
