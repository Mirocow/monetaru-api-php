<?php

namespace AvtoDev\MonetaApi\Clients;

use AvtoDev\MonetaApi\Types\Fine;
use AvtoDev\MonetaApi\Support\FineCollection;
use AvtoDev\MonetaApi\Types\Requests\Payments\PaymentRequest;
use AvtoDev\MonetaApi\Types\Requests\Payments\PaymentBatchRequest;

class PaymentsApiCommands extends AbstractApiCommands
{
    /**
     * @param Fine $fine
     *
     * @return PaymentRequest
     */
    public function payOne(Fine $fine)
    {
        $request = $this->transfer()
            ->setAccountNumber($this->api->getConfigValue('accounts.fines.id'))
            ->setDestinationAccount($this->api->getConfigValue('accounts.provider.id'))
            ->setPaymentPassword($this->api->getConfigValue('accounts.fines.password'))
            ->setFine($fine);

        return $request;
    }

    public function payButch(FineCollection $fines)
    {
        $request = new PaymentBatchRequest($this->api, $fines);

        return $request;
    }

    /**
     * @return PaymentRequest
     */
    public function transfer()
    {
        $request = new PaymentRequest($this->api);
        $request->setIsPayerAmount();

        return $request;
    }
}
