<?php

namespace AvtoDev\MonetaApi\Clients;

use AvtoDev\MonetaApi\Types\Requests\FinesRequest;

class FinesApiCommands extends AbstractApiCommands
{
    public function find()
    {
        $request = new FinesRequest($this->api);

        return $request;
    }
}
