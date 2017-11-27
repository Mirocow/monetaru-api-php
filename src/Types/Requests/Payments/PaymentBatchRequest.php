<?php

namespace AvtoDev\MonetaApi\Types\Requests\Payments;

use AvtoDev\MonetaApi\Support\FineCollection;
use AvtoDev\MonetaApi\Types\Attributes\MonetaAttribute;
use AvtoDev\MonetaApi\References\PaymentRequestReference;

/**
 * Class PaymentBatchRequest.
 *
 * Пакетная отправка PaymentRequest
 *
 * @see PaymentRequest
 *
 * @todo: На тестовой площадке выбивает Access id denied
 */
class PaymentBatchRequest extends AbstractPaymentRequest
{
    protected $transactions = [];

    protected $methodName = 'PaymentBatchRequest';

    /**
     * @var FineCollection
     */
    protected $fines;

    public function setIsTransactional($isTransactional = true)
    {
        $this->attributes->push(
            new MonetaAttribute(PaymentRequestReference::TRANSACTION_FIELD_TRANSACTIONAL, $isTransactional)
        );

        return $this;
    }

    public function setExitOnFailure($isExitOnFailure = true)
    {
        $this->attributes->push(
            new MonetaAttribute(PaymentRequestReference::TRANSACTION_FIELD_EXIT_ON_FAILURE, $isExitOnFailure)
        );

        return $this;
    }

    public function prepare($response)
    {
        return $response;
    }

    public function setFines(FineCollection $fines)
    {
        $this->fines = $fines;

        return $this;
    }

    protected function processFines()
    {
        foreach ($this->fines as $fine) {
            $request              = $this->api->payments()
                ->payOne($fine)
                ->setPayerPhone($this->phone)
                ->setPayerFio($this->fio)
                ->toJson();
            $this->transactions[] = json_decode($request)
                ->Envelope
                ->Body
                ->PaymentRequest;
        }
    }

    protected function createBody()
    {
        $this->processFines();
        $attributes = [];
        foreach ($this->attributes as $attribute) {
            $attributes[$attribute->getName()] = $attribute->getValue();
        }

        return array_merge(
            $attributes,
            [
                'transaction' => $this->transactions,
            ]
        );
    }
}
