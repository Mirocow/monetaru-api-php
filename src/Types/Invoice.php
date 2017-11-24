<?php

namespace AvtoDev\MonetaApi\Types;

use Carbon\Carbon;

class Invoice extends AbstractType
{
    /**
     * Номер транзакции в системе МОНЕТА.РУ.
     *
     * @var int
     */
    protected $transactionId;

    /**
     * @var Carbon
     */
    protected $dateTime;

    /**
     * Статус операции.
     *
     * @var string
     */
    protected $status;

    /**
     * Ид операции в системе клиента.
     *
     * @var string
     */
    protected $clientTransactionId;

    /**
     * {@inheritdoc}
     *
     * @todo: добавить справочник
     */
    public function configure($content)
    {
        $config = $this->convertToArray($content);
        foreach ($config as $key => $value) {
            switch ($key) {
                case 'transaction':
                    $this->transactionId = (int) $value;
                    break;
                case 'dateTime':
                    $this->dateTime = $this->convertToCarbon($value);
                    break;
                case 'status':
                    $this->status = $value;
                    break;
                case 'clientTransaction':
                    $this->clientTransactionId = $value;
                    break;
            }
        }
    }

    /**
     * @return int
     */
    public function getTransactionId()
    {
        return $this->transactionId;
    }

    /**
     * @return Carbon
     */
    public function getDateTime()
    {
        return $this->dateTime;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getClientTransactionId()
    {
        return $this->clientTransactionId;
    }

    public function getPaymentUrl()
    {
        return 'https://www.payanyway.ru/assistant.htm?operationId='
               . $this->getTransactionId()
               . '&paymentSystem.unitId=card&paymentSystem.limitIds=card&followup=true';
    }
}
