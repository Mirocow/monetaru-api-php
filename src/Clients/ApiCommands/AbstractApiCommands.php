<?php

namespace AvtoDev\MonetaApi\Clients\ApiCommands;

use AvtoDev\MonetaApi\Clients\MonetaApi;

/**
 * Class AbstractApiCommands.
 */
abstract class AbstractApiCommands
{
    /**
     * Инстанс api-клиента.
     *
     * @var MonetaApi
     */
    protected $api;

    /**
     * AbstractApiCommands constructor.
     *
     * @param MonetaApi $api
     */
    public function __construct(MonetaApi $api)
    {
        $this->api = $api;
    }
}
