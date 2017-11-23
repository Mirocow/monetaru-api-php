<?php

namespace AvtoDev\MonetaApi\Tests\Exceptions;

use PHPUnit\Framework\TestCase;
use AvtoDev\MonetaApi\Exceptions\AbstractMonetaException;

abstract class AbstractExceptionTest extends TestCase
{
    /**
     * @var AbstractMonetaException
     */
    protected $exceptionClass;

    public function testMonetaCode()
    {
        try {
            $this->throwMonetaException('', '400.1.8.1');
        } catch (AbstractMonetaException $exception) {
            $this->assertEquals('400.1.8.1', $exception->getMonetaExceptionCode());
        }
    }

    public function testMonetaMessage()
    {
        $this->expectException($this->exceptionClass);
        $this->expectExceptionMessage('Невозможно сделать возврат так как операция незавершена');
        $this->throwMonetaException('Невозможно сделать возврат так как операция незавершена', '400.1.8.1');

        $this->expectException($this->exceptionClass);
        $this->expectExceptionMessage('Невозможно сделать возврат так как операция незавершена. Test');

        $this->throwMonetaException('Test', '400.1.8.1');
    }

    abstract protected function throwMonetaException($message, $code);
}
