<?php

namespace AvtoDev\MonetaApi\Types\Requests;

use AvtoDev\MonetaApi\Types\Provider;
use AvtoDev\MonetaApi\Types\Attributes\MonetaAttribute;
use AvtoDev\MonetaApi\References\ProviderRequestReference;

/**
 * Class FindServiceProviderByIdRequest.
 *
 * Поиск провайдера по ID
 *
 * @see ProviderRequestReference
 */
class FindServiceProviderByIdRequest extends AbstractRequest
{
    /**
     * {@inheritdoc}
     */
    protected $methodName = 'FindServiceProviderByIdRequest';

    protected $required   = [
        ProviderRequestReference::FIELD_PROVIDER_ID,
    ];

    /**
     * {@inheritdoc}
     *
     * @return Provider
     */
    public function prepare($response)
    {
        return new Provider($response->FindServiceProviderByIdResponse->provider);
    }

    /**
     * Устанавливает ID поиска.
     *
     * @param $id
     *
     * @return $this
     */
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
