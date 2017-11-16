<?php

namespace AvtoDev\MonetaApi\Types\Requests;

use AvtoDev\MonetaApi\Types\Fine;
use AvtoDev\MonetaApi\HttpClientInterface;
use AvtoDev\MonetaApi\Types\Attributes\MonetaAttribute;
use AvtoDev\MonetaApi\References\PaymentRequestReference;
use AvtoDev\MonetaApi\Exceptions\MonetaBadRequestException;

class PaymentRequest extends AbstractRequest
{
    protected $methodName = 'PaymentRequest';

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

    /**
     * @throws MonetaBadRequestException
     *
     * @return mixed
     */
    public function exec()
    {
        if (! $this->hasAttributeByType(PaymentRequestReference::FIELD_PAYER)) {
            throw new MonetaBadRequestException('Не указан обязательный параметр ' . PaymentRequestReference::FIELD_PAYER,
                '500.1.6');
        }

        return parent::exec();
    }

    protected function createBody()
    {
        $attributes = [];
        foreach ($this->attributes() as $attribute) {
            $attributes[] = $attribute->toAttribute('key');
        }

        $operationInfoAttributes = $this->fine->getOperationInfo();

        $operationInfo = [];
        /**
         * @var MonetaAttribute
         */
        foreach ($operationInfoAttributes as $attribute) {
            $operationInfo[] = $attribute->toAttribute('key');
        }

        return [
            'version'                                     => $this->version,
            $attributes,
            PaymentRequestReference::FIELD_OPERATION_INFO => [
                'attribute' => $operationInfo,
            ],
        ];
    }
}
