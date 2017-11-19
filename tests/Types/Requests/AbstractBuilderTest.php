<?php

namespace AvtoDev\MonetaApi\Tests;

use PHPUnit\Framework\TestCase;
use AvtoDev\MonetaApi\HttpClientInterface;
use AvtoDev\MonetaApi\HttpClients\GuzzleHttpClient;
use AvtoDev\MonetaApi\Types\Attributes\MonetaAttribute;
use AvtoDev\MonetaApi\Exceptions\MonetaBadRequestException;
use AvtoDev\MonetaApi\Exceptions\MonetaBadSettingsException;
use AvtoDev\MonetaApi\Exceptions\MonetaServerErrorException;
use AvtoDev\MonetaApi\Tests\Types\Requests\Mock\RequestMock;

/**
 * Class AbstractBuilderTest.
 *
 * @group requests
 */
class AbstractBuilderTest extends TestCase
{
    /**
     * @var HttpClientInterface|RequestMock
     */
    protected $builder;

    /**
     * @var \Mockery\MockInterface
     */
    protected $http;

    protected function setUp()
    {
        parent::setUp();
        $this->http = \Mockery::mock(GuzzleHttpClient::class);
        $this->http->shouldReceive('post')->once()->andReturn('{"Envelope":{"Body":{"key":"value"}}}');

        $this->http->shouldReceive('lastStatusCode')->once()->andReturn(200);
        $this->builder = new RequestMock($this->http, ['header' => 'content'], '91');
    }

    public function testAttributeSet()
    {
        $this->assertFalse($this->builder->hasAttributeByValue('value'));

        $this->builder->pushAttribute($attribute = new MonetaAttribute('test', 'value'));

        $this->assertInstanceOf(
            \stdClass::class,
            $response = $this->builder->exec()
        );

        $this->assertEquals('value', $response->key);
    }

    public function testClientException()
    {
        $this->expectException(MonetaBadRequestException::class);
        $this->expectExceptionMessage('all fine Ошибки при валидации данных');
        $this->expectExceptionCode(400);

        $this->http->shouldReceive('post')->once()
            ->andReturn(
                '{"Envelope":{"Body":{"fault":{"faultstring":"all fine","faultcode":"Client","detail":{"faultDetail":"500"}}}}}'
            );
        $this->http->shouldReceive('lastStatusCode')->once()
            ->andReturn(
                400
            );
        $this->builder->exec();
        $this->builder->exec();
    }

    public function testServerException()
    {
        $this->expectException(MonetaServerErrorException::class);
        $this->expectExceptionCode(400);
        $this->expectExceptionMessage('all fine Сервис временно недоступен');

        $this->http->shouldReceive('post')->once()
            ->andReturn(
                '{"Envelope":{"Body":{"fault":{"faultstring":"all fine","faultcode":"Server","detail":{"faultDetail":"100"}}}}}'
            );

        $this->http->shouldReceive('lastStatusCode')->once()
            ->andReturn(
                400
            );
        $this->builder->exec();
        $this->builder->exec();
    }

    public function testUnknownException()
    {
        $this->expectException(MonetaBadSettingsException::class);

        $this->http->shouldReceive('post')->once()
            ->andReturn(
                '{"Envelope":{"Body":{"fault":{"faultstring":"all fine","faultcode":"Some","detail":{"faultDetail":"100"}}}}}'
            );

        $this->http->shouldReceive('lastStatusCode')->once()
            ->andReturn(
                400
            );
        $this->builder->exec();
        $this->builder->exec();
    }

    public function testAttributeToArray()
    {
        $attribute = new MonetaAttribute('key', 'value');
        $this->assertJsonStringEqualsJsonString('{"key":"value"}', $attribute->toJson());
    }
}
