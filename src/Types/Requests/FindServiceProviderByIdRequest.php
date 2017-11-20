<?php

namespace AvtoDev\MonetaApi\Types\Requests;

use AvtoDev\MonetaApi\Types\Provider;
use AvtoDev\MonetaApi\Types\Attributes\MonetaAttribute;
use AvtoDev\MonetaApi\References\ProviderRequestReference;

class FindServiceProviderByIdRequest extends AbstractRequest
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
        $this->pushAttribute(new MonetaAttribute(ProviderRequestReference::FIELD_PROVIDER_ID, $id));

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
