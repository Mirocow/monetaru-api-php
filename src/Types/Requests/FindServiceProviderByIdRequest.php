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

    /**
     * {@inheritdoc}
     */
    protected $responseName = 'FindServiceProviderByIdResponse';

    /**
     * {@inheritdoc}
     */
    protected $required = [
        ProviderRequestReference::FIELD_PROVIDER_ID,
    ];

    /**
     * {@inheritdoc}
     *
     * @return Provider
     */
    public function exec()
    {
        return parent::exec();
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

    /**
     * {@inheritdoc}
     *
     * @return Provider
     */
    protected function prepare($response)
    {
        return new Provider($response['provider']);
    }

    protected function createBody()
    {
        return [
            'providerId' => $this->attributes->getByType(ProviderRequestReference::FIELD_PROVIDER_ID)->getValue(),
            'version'    => $this->version,
        ];
    }
}
