<?php

namespace AvtoDev\MonetaApi\Tests\Exceptions;

use AvtoDev\MonetaApi\Exceptions\MonetaBadRequestException;

class MonetaBadRequestExceptionTest extends AbstractExceptionTest
{
    protected $exceptionClass = MonetaBadRequestException::class;

    protected function throwMonetaException($message, $code)
    {
        throw new MonetaBadRequestException($message, $code);
    }
}
