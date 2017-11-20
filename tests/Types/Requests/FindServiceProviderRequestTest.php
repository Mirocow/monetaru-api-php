<?php

namespace AvtoDev\MonetaApi\Tests\Types\Requests;

use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use AvtoDev\MonetaApi\Types\Provider;
use AvtoDev\MonetaApi\HttpClientInterface;
use AvtoDev\MonetaApi\HttpClients\GuzzleHttpClient;
use AvtoDev\MonetaApi\Types\Attributes\MonetaAttribute;
use AvtoDev\MonetaApi\Types\Requests\FindServiceProviderByIdRequest;

class FindServiceProviderRequestTest extends TestCase
{
    /**
     * @var FindServiceProviderByIdRequest
     */
    protected $builder;

    /**
     * @var HttpClientInterface|MockInterface
     */
    protected $http;

    protected function setUp()
    {
        parent::setUp();
        $this->http    = \Mockery::mock(GuzzleHttpClient::class);
        $mock          = new FindServiceProviderByIdRequest($this->http, ['header' => 'content'], '91');
        $this->builder = \Mockery::mock($mock)->shouldAllowMockingProtectedMethods()->makePartial();
    }

    public function testById()
    {
        $json = file_get_contents(__DIR__ . '/Mock/FindProviderByIdResponse.json');
        $this->http->shouldReceive('post')->andReturn($json);
        $this->http->shouldReceive('lastStatusCode')->andReturn(200);
        $id           = '123';
        $providerType = 'providerId';
        $return       = $this->builder->byId($id)->exec();
        $this->assertInstanceOf(Provider::class, $return);
        $this->assertInstanceOf(MonetaAttribute::class, $this->builder->getAttributeByType($providerType));
        $this->assertEquals($id, $this->builder->getAttributeByType($providerType)->getValue());
    }
}
