<?php

namespace AvtoDev\MonetaApi\Clients\ApiCommands;

use AvtoDev\MonetaApi\Types\Requests\FindServiceProviderByIdRequest;

class ServiceProviderApiCommands extends AbstractApiCommands
{
    /**
     * Получить данные по id провайдера.
     *
     * @param string $id
     *
     * @return FindServiceProviderByIdRequest
     */
    public function getById($id)
    {
        $findProviderRequest = new FindServiceProviderByIdRequest($this->api);
        $findProviderRequest->byId($id);

        return $findProviderRequest;
    }
}
