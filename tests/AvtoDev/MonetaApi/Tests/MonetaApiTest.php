<?php

namespace AvtoDev\MonetaApi\Tests;

use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use AvtoDev\MonetaApi\Types\Fine;
use AvtoDev\MonetaApi\MonetaApi as Api;
use AvtoDev\MonetaApi\Types\Requests\FinesRequest;
use AvtoDev\MonetaApi\Types\Requests\PaymentRequest;
use AvtoDev\MonetaApi\Types\Attributes\MonetaAttribute;
use AvtoDev\MonetaApi\Exceptions\MonetaBadSettingsException;
use AvtoDev\MonetaApi\Types\Requests\FindServiceProviderByIdRequest;

class MonetaApiTest extends TestCase
{
    /**
     * @var Api|MockInterface
     */
    protected $api;

    protected function setUp()
    {
        parent::setUp();
        $config    = [
            'authorization' => [
                'username' => 'i',
                'password' => 'need',
            ],
            'accounts'      => [
                'fines_account'      => 'some',
                'commission_account' => 'body',
            ],
        ];
        $api       = new Api($config);
        $this->api = \Mockery::mock($api)->shouldAllowMockingProtectedMethods()->makePartial();
    }

    public function testFindFines()
    {
        $this->assertInstanceOf(FinesRequest::class, $this->api->findFines());
    }

    public function testServiceProvider()
    {
        $this->assertInstanceOf(FindServiceProviderByIdRequest::class, $this->api->getServiceProvider());
    }

    public function testPayRequest()
    {
        $this->assertInstanceOf(PaymentRequest::class, $request = $this->api->payRequest(new Fine));
        $this->assertTrue($request->hasAttributeByType('payer'));
        $this->assertInstanceOf(MonetaAttribute::class, $request->getAttributeByType('payer'));
        $this->assertEquals('some', $request->getAttributeByType('payer')->getValue());
    }

    public function testBadSettings()
    {
        $this->expectException(MonetaBadSettingsException::class);
        $api = new Api;
    }
}
