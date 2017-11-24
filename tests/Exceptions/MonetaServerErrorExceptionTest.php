<?php

namespace AvtoDev\MonetaApi\Tests\Exceptions;

use AvtoDev\MonetaApi\Exceptions\MonetaServerErrorException;

class MonetaServerErrorExceptionTest extends AbstractExceptionTest
{
    protected $exceptionClass = MonetaServerErrorException::class;

    protected function throwMonetaException($message, $code)
    {
        throw new MonetaServerErrorException($message, $code);
    }
}
