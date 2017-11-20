<?php

namespace AvtoDev\MonetaApi\Tests\Types\Requests;

use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use AvtoDev\MonetaApi\Types\Fine;
use AvtoDev\MonetaApi\HttpClientInterface;
use AvtoDev\MonetaApi\Types\Requests\FinesRequest;
use AvtoDev\MonetaApi\HttpClients\GuzzleHttpClient;
use AvtoDev\MonetaApi\Exceptions\MonetaBadRequestException;

class FinesRequestTest extends TestCase
{
    /**
     * @var FinesRequest
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
        $mock          = new FinesRequest($this->http, ['header' => 'content'], '91');
        $this->builder = \Mockery::mock($mock)->shouldAllowMockingProtectedMethods()->makePartial();
    }

    public function testByUin()
    {
        $this->prepareSuccessfulResponse();

        $uin            = '1234';
        $searchMethod   = 'CUSTOMFIELD:200';
        $byUinAttribute = 'CUSTOMFIELD:101';

        $response = $this->builder->byUin($uin)->includeNonPaid()->exec();
        $this->assertTrue($this->builder->hasAttributeByType($searchMethod));
        $this->assertTrue($this->builder->hasAttributeByType($searchMethod));
        $this->assertEquals(0, $this->builder->getAttributeByType($searchMethod)->getValue());

        $this->assertTrue($this->builder->hasAttributeByType($byUinAttribute));
        $this->assertEquals($uin, $this->builder->getAttributeByType($byUinAttribute)->getValue());

        $this->assertTrue(is_array($response));
        foreach ($response as $fine) {
            $this->assertInstanceOf(Fine::class, $fine);
        }
    }

    public function testBySts()
    {
        $this->prepareSuccessfulResponse();

        $sts            = '1234';
        $searchMethod   = 'CUSTOMFIELD:200';
        $byStsAttribute = 'CUSTOMFIELD:102';

        $response = $this->builder->bySTS($sts)->exec();

        $this->assertTrue($this->builder->hasAttributeByType($searchMethod));
        $this->assertEquals(1, $this->builder->getAttributeByType($searchMethod)->getValue());

        $this->assertTrue($this->builder->hasAttributeByType($byStsAttribute));
        $this->assertEquals($sts, $this->builder->getAttributeByType($byStsAttribute)->getValue());

        $this->assertTrue(is_array($response));
        foreach ($response as $fine) {
            $this->assertInstanceOf(Fine::class, $fine);
        }
    }

    public function testByDriverLicense()
    {
        $this->prepareSuccessfulResponse();

        $license                  = '1234';
        $searchMethod             = 'CUSTOMFIELD:200';
        $byDrivelLicenseAttribute = 'CUSTOMFIELD:103';

        $response = $this->builder->byDriverLicense($license)->exec();

        $this->assertTrue($this->builder->hasAttributeByType($searchMethod));
        $this->assertEquals(1, $this->builder->getAttributeByType($searchMethod)->getValue());

        $this->assertTrue($this->builder->hasAttributeByType($byDrivelLicenseAttribute));
        $this->assertEquals($license, $this->builder->getAttributeByType($byDrivelLicenseAttribute)->getValue());

        $this->assertTrue(is_array($response));
        foreach ($response as $fine) {
            $this->assertInstanceOf(Fine::class, $fine);
        }
    }

    public function testFilterDate()
    {
        $dateFrom = '2017-01-01';
        $dateTo   = '2017-02-01';
        $this->builder->dateFrom($dateFrom);

        $dateFromName = 'CUSTOMFIELD:112';
        $dateToName   = 'CUSTOMFIELD:113';

        $this->assertTrue($this->builder->hasAttributeByType($dateFromName));
        $this->assertEquals($dateFrom, $this->builder->getAttributeByType($dateFromName)->getValue());

        $this->builder->dateTo($dateTo);

        $this->assertTrue($this->builder->hasAttributeByType($dateToName));
        $this->assertEquals($dateTo, $this->builder->getAttributeByType($dateToName)->getValue());
    }

    public function testNotFound()
    {
        $json = file_get_contents(__DIR__ . '/Mock/EmptyFineResponse.json');
        $this->http->shouldReceive('post')
            ->once()
            ->andReturn($json);

        $this->http->shouldReceive('lastStatusCode')
            ->once()
            ->andReturn(200);

        $response = $this->builder->bySTS('00')->exec();
        $this->assertTrue(is_array($response));
        $this->assertEmpty($response);
        $this->assertCount(0, $response);
    }

    public function testBody()
    {
        $this->assertNotNull($body = $this->builder->createBody());
        $this->assertArrayHasKey('version', $body);
        $this->assertArrayHasKey('providerId', $body);
        $this->assertArrayHasKey('fieldsInfo', $body);
    }

    public function testException()
    {
        $this->expectException(MonetaBadRequestException::class);
        $this->builder->exec();
    }

    protected function prepareSuccessfulResponse()
    {
        $json = file_get_contents(__DIR__ . '/Mock/FineResponse.json');
        $this->http->shouldReceive('post')
            ->once()
            ->andReturn($json);

        $this->http->shouldReceive('lastStatusCode')
            ->once()
            ->andReturn(200);
    }
}
