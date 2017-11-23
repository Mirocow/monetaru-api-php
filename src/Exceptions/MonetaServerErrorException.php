<?php

namespace AvtoDev\MonetaApi\Exceptions;

/**
 * Class MonetaServerErrorException.
 *
 * Ошибка сервера МОНЕТА.RU
 */
class MonetaServerErrorException extends AbstractMonetaException
{
    /**
     * Код ошибки который должен быть подставлен по умолчанию.
     *
     * @var int
     */
    protected $httpExceptionCode = 500;
}
