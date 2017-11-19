<?php

namespace AvtoDev\MonetaApi\Types\Requests;

use AvtoDev\MonetaApi\Types\Provider;

class FindServiceProviderRequest extends AbstractRequest
{
    public $providerId;

    protected $methodName = 'FindServiceProviderByIdRequest';

    public function prepare($response)
    {
        return new Provider($response->FindServiceProviderByIdResponse->provider);
    }

    public function byId($id)
    {
        $this->providerId = $id;

        return $this;
    }

    protected function createBody()
    {
        return [
            'providerId' => $this->providerId,
            'version'    => $this->version,
        ];
    }
}
