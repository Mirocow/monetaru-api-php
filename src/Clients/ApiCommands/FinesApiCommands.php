<?php

namespace AvtoDev\MonetaApi\Clients\ApiCommands;

use AvtoDev\MonetaApi\Types\Requests\FinesRequest;

/**
 * Class FinesApiCommands.
 *
 * Класс содержищий в себе методы работы со штрафами
 */
class FinesApiCommands extends AbstractApiCommands
{
    /**
     * @return FinesRequest
     */
    public function find()
    {
        $request = new FinesRequest($this->api);

        return $request;
    }
}
