<?php

namespace AvtoDev\MonetaApi\Tests;

use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use AvtoDev\MonetaApi\Types\Fine;
use AvtoDev\MonetaApi\Support\FineCollection;
use AvtoDev\MonetaApi\Clients\MonetaApi as Api;
use AvtoDev\MonetaApi\Types\Requests\FinesRequest;
use AvtoDev\MonetaApi\Types\Attributes\MonetaAttribute;
use AvtoDev\MonetaApi\Exceptions\MonetaBadSettingsException;
use AvtoDev\MonetaApi\Tests\Types\Requests\Mock\RequestMock;
use AvtoDev\MonetaApi\Types\Requests\Payments\InvoiceRequest;
use AvtoDev\MonetaApi\Types\Requests\Payments\PaymentRequest;
use AvtoDev\MonetaApi\Types\Requests\Payments\PaymentBatchRequest;
use AvtoDev\MonetaApi\Types\Requests\FindServiceProviderByIdRequest;
use AvtoDev\MonetaApi\Types\Requests\Payments\GetOperationDetailsRequest;

class MonetaApiTest extends TestCase
{
    /**
     * @var Api|MockInterface
     */
    protected $api;

    /**
     * @var array
     */
    protected $config = [
    ];

    protected function setUp()
    {
        parent::setUp();
        $this->config = [
            'authorization' => [
                'username' => 'i',
                'password' => 'need',
            ],
            'accounts'      => [
                'fines'      => [
                    'id' => 'some',
                ],
                'commission' => [
                    'id' => 'body',
                ],
            ],
            'is_test'       => true,
        ];
        $api          = new Api($this->config);
        $this->api    = \Mockery::mock($api)->shouldAllowMockingProtectedMethods()->makePartial();
    }

    public function testFindFines()
    {
        $this->assertInstanceOf(FinesRequest::class, $this->api->fines()->find());
    }

    public function testServiceProvider()
    {
        $this->assertInstanceOf(
            FindServiceProviderByIdRequest::class,
            $this->api->serviceProvider()->getById('123')
        );
    }

    public function testPayRequest()
    {
        $this->assertInstanceOf(PaymentRequest::class, $request = $this->api->payments()->payOne(new Fine));
        $request = \Mockery::mock($request)->shouldAllowMockingProtectedMethods()->makePartial();
        $this->assertTrue($request->getAttributes()->hasByType('payer'));
        $this->assertInstanceOf(MonetaAttribute::class, $request->getAttributes()->getByType('payer'));
        $this->assertEquals('some', $request->getAttributes()->getByType('payer')->getValue());
    }

    public function testBadSettings()
    {
        $this->expectException(MonetaBadSettingsException::class);
        new Api;
    }

    public function testBadHttpClient()
    {
        $this->expectException(MonetaBadSettingsException::class);
        $this->expectExceptionMessage('Данный вид http клиента не поддерживается');
        $this->config['use_http_client'] = 'curl';
        new Api($this->config);
    }

    public function testNotFoundTestResponse()
    {
        $builder = new RequestMock($this->api);
        $json    = '{"key": "value","attribute": [{"key": "name","value": "value"}]}';
        $this->assertEquals(
            json_decode($json, true),
            $response = $builder->setMethodName('empty')->setTest('test')->exec()
        );
    }

    public function testPayButch()
    {
        $collection = new FineCollection;
        $collection->push(new Fine);
        $this->assertInstanceOf(PaymentBatchRequest::class, $this->api->payments()->payButch($collection));
    }

    public function testInvoice()
    {
        $this->assertInstanceOf(InvoiceRequest::class, $this->api->payments()->invoice());
    }

    public function testOperationDetails()
    {
        $this->assertInstanceOf(GetOperationDetailsRequest::class, $this->api->payments()->getOperationDetails());
    }
}
