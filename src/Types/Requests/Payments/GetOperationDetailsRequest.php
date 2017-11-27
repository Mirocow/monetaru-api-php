<?php

namespace AvtoDev\MonetaApi\Types\Requests\Payments;

use AvtoDev\MonetaApi\Types\OperationDetails;
use AvtoDev\MonetaApi\Types\Requests\AbstractRequest;

class GetOperationDetailsRequest extends AbstractRequest
{
    protected $methodName   = 'GetOperationDetailsByIdRequest';

    protected $responseName = 'GetOperationDetailsByIdResponse';

    protected $id;

    /**
     * {@inheritdoc}
     *
     * @return OperationDetails
     */
    public function prepare($response)
    {
        return new OperationDetails($response);
    }

    /**
     * {@inheritdoc}
     *
     * @return OperationDetails
     */
    public function exec()
    {
        return parent::exec();
    }

    /**
     * ИД транзакции.
     *
     * @param (string) $id
     *
     * @return $this
     */
    public function byId($id)
    {
        $this->id = (string) trim($id);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function createBody()
    {
        return (int) $this->id;
    }
}
