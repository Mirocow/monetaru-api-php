<?php

namespace AvtoDev\MonetaApi\Types\Requests\Payments;

use AvtoDev\MonetaApi\Types\Requests\AbstractRequest;

class GetOperationDetailsRequest extends AbstractRequest
{
    protected $methodName = 'GetOperationDetailsRequest';

    protected $id;

    public function prepare($response)
    {
        // @todo: Implement prepare() method.
    }

    /**
     * ИД транзакции.
     *
     * @param (string) $id
     */
    public function byId($id)
    {
        $this->id = (string) trim($id);
    }

    protected function createBody()
    {
        return [$this->id];
    }
}
