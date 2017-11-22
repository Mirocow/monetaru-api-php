<?php

namespace AvtoDev\MonetaApi\Clients;

abstract class AbstractApiCommands
{
    protected $api;

    public function __construct(MonetaApi $api)
    {
        $this->api = $api;
    }
}
