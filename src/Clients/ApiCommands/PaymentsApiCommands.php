<?php

namespace AvtoDev\MonetaApi\Clients\ApiCommands;

use AvtoDev\MonetaApi\Types\Fine;
use AvtoDev\MonetaApi\Support\FineCollection;
use AvtoDev\MonetaApi\Types\Requests\Payments\InvoiceRequest;
use AvtoDev\MonetaApi\Types\Requests\Payments\PaymentRequest;
use AvtoDev\MonetaApi\Types\Requests\Payments\PaymentBatchRequest;
use AvtoDev\MonetaApi\Types\Requests\Payments\GetOperationDetailsRequest;

/**
 * Class PaymentsApiCommands.
 *
 * Класс содержащий методы работы с платежами
 */
class PaymentsApiCommands extends AbstractApiCommands
{
    /**
     * Оплата одного штрафа.
     *
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

    /**
     * Пакетная оплата штрафов.
     *
     * @param FineCollection $fines
     *
     * @return PaymentBatchRequest
     */
    public function payButch(FineCollection $fines)
    {
        $request = new PaymentBatchRequest($this->api);
        $request->setIsTransactional(false)->setExitOnFailure(false)->setFines($fines);

        return $request;
    }

    /**
     * Перевод денег между счетами.
     *
     * @return PaymentRequest
     */
    public function transfer()
    {
        $request = new PaymentRequest($this->api);
        $request->setIsPayerAmount();

        return $request;
    }

    /**
     * Выставление счета (используется для получения токена для рекарринга).
     *
     * @return InvoiceRequest
     */
    public function invoice()
    {
        $request = new InvoiceRequest($this->api);

        return $request;
    }

    /**
     * Получить информацию о платеже.
     *
     * @return GetOperationDetailsRequest
     */
    public function getOperationDetails()
    {
        return new GetOperationDetailsRequest($this->api);
    }
}
