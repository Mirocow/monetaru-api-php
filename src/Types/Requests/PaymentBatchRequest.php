<?php

namespace AvtoDev\MonetaApi\Types\Requests;

use AvtoDev\MonetaApi\Types\Fine;
use AvtoDev\MonetaApi\HttpClientInterface;
use AvtoDev\MonetaApi\Types\Attributes\MonetaAttribute;
use AvtoDev\MonetaApi\References\PaymentRequestReference;

class PaymentBatchRequest extends AbstractRequest
{
    protected $transactions = [];

    public function __construct(HttpClientInterface $httpClient,
                                array $header,
                                $providerId)
    {
        parent::__construct($httpClient, $header, $providerId);
        $this->pushAttribute(new MonetaAttribute(PaymentRequestReference::TRANSACTION_FIELD_TRANSACTIONAL, 1));
        $this->pushAttribute(new MonetaAttribute(PaymentRequestReference::TRANSACTION_FIELD_EXIT_ON_FAILURE, 1));
    }

    public function transactional($isTransactional = true)
    {
        $this->pushAttribute(
            new MonetaAttribute(PaymentRequestReference::TRANSACTION_FIELD_TRANSACTIONAL, $isTransactional)
        );

        return $this;
    }

    public function exitOnFailure($isExitOnFailure = true)
    {
        $this->pushAttribute(
            new MonetaAttribute(PaymentRequestReference::TRANSACTION_FIELD_EXIT_ON_FAILURE, $isExitOnFailure)
        );

        return $this;
    }

    public function prepare($response)
    {
        // @todo: Implement prepare() method.
    }

    public function setFInes($fines)
    {
        $body = [];
        /** @var Fine $fine */
        foreach ($fines as $fine) {
            $request = new PaymentRequest($this->httpClient, $this->header, $this->providerId, $fine);
            $body[]  = $request->getJson();
        }
    }

    protected function createBody()
    {
    }
}
