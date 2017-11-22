<?php

namespace AvtoDev\MonetaApi\Tests\Types\Requests;

use Mockery\MockInterface;
use AvtoDev\MonetaApi\Exceptions\MonetaBadRequestException;
use AvtoDev\MonetaApi\Exceptions\MonetaServerErrorException;
use AvtoDev\MonetaApi\Tests\Types\Requests\Mock\RequestMock;

/**
 * Class AbstractBuilderTest.
 *
 * @group requests
 * @group types
 */
class AbstractBuilderTest extends AbstractRequestTestCase
{
    /**
     * @var MockInterface|RequestMock
     */
    protected $builder;

    protected function setUp()
    {
        parent::setUp();

        $this->builder = new RequestMock($this->api);
    }

    public function testTest()
    {
        $this->assertInstanceOf(\stdClass::class, $this->builder->setTest('value')->exec());
    }

    public function testRequiredException()
    {
        $this->expectException(MonetaBadRequestException::class);
        $this->expectExceptionMessage('Не заполнен обязательный атрибут: test');
        $this->builder->exec();
    }

    public function testServerException()
    {
        $this->expectException(MonetaServerErrorException::class);
        $this->builder->setMethodName('ServerExceptionExample')->setTest('value')->exec();
    }

    public function testClientException()
    {
        $this->expectException(MonetaBadRequestException::class);
        $this->builder->setMethodName('ValidationExceptionExample')->setTest('value')->exec();
    }
}
