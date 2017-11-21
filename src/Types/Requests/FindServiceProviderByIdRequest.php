<?php

namespace AvtoDev\MonetaApi\Types\Requests;

use AvtoDev\MonetaApi\Types\Provider;
use AvtoDev\MonetaApi\Types\Attributes\MonetaAttribute;
use AvtoDev\MonetaApi\References\ProviderRequestReference;

class FindServiceProviderByIdRequest extends AbstractRequest
{
    protected $methodName = 'FindServiceProviderByIdRequest';

    protected $required   = [
        ProviderRequestReference::FIELD_PROVIDER_ID,
    ];

    public function prepare($response)
    {
        return new Provider($response->FindServiceProviderByIdResponse->provider);
    }

    public function byId($id)
    {
        $this->attributes->push(new MonetaAttribute(ProviderRequestReference::FIELD_PROVIDER_ID, $id));

        return $this;
    }

    protected function createBody()
    {
        return [
            'providerId' => $this->attributes->getByType(ProviderRequestReference::FIELD_PROVIDER_ID)->getValue(),
            'version'    => $this->version,
        ];
    }
}
