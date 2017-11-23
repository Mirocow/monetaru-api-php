<?php

namespace AvtoDev\MonetaApi\Clients;

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
