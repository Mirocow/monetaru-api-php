<?php

namespace AvtoDev\MonetaApi\Types\Requests\Payments;

use AvtoDev\MonetaApi\Clients\MonetaApi;
use AvtoDev\MonetaApi\Support\FineCollection;
use AvtoDev\MonetaApi\Types\Attributes\MonetaAttribute;
use AvtoDev\MonetaApi\References\PaymentRequestReference;

class PaymentBatchRequest extends AbstractPaymentRequest
{
    protected $transactions = [];

    public function __construct(MonetaApi $api,
                                FineCollection $fines)
    {
        parent::__construct($api);
        $this->setIsTransactional()->setExitOnFailure()->setFines($fines);
    }

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
        // @todo: Implement prepare() method.
    }

    public function setFines(FineCollection $fines)
    {
        // @todo: Implement setFines() method.
    }

    protected function createBody()
    {
        // @todo: Implement createBody() method.
    }
}
