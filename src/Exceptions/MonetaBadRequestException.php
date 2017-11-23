<?php

namespace AvtoDev\MonetaApi\Exceptions;

/**
 * Class MonetaBadRequestException.
 *
 * Ошибка запроса
 */
class MonetaBadRequestException extends AbstractMonetaException
{
    /**
     * Код ошибки который должен быть подставлен по умолчанию.
     *
     * @var int
     */
    protected $httpExceptionCode = 400;
}
