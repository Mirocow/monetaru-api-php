<?php

namespace AvtoDev\MonetaApi\Tests\Types\Requests;

use Mockery\MockInterface;
use AvtoDev\MonetaApi\Types\Fine;
use AvtoDev\MonetaApi\Support\FineCollection;
use AvtoDev\MonetaApi\Types\Requests\FinesRequest;
use AvtoDev\MonetaApi\Exceptions\MonetaBadRequestException;

/**
 * Class FinesRequestTest.
 *
 * @group requests
 * @group types
 */
class FinesRequestTest extends AbstractRequestTestCase
{
    /**
     * @var FinesRequest|MockInterface
     */
    protected $builder;

    protected function setUp()
    {
        parent::setUp();
        $mock          = new FinesRequest($this->api);
        $this->builder = \Mockery::mock($mock)->shouldAllowMockingProtectedMethods()->makePartial();
    }

    public function testByUin()
    {
        $uin            = ['1234', '4321'];
        $searchMethod   = 'CUSTOMFIELD:200';
        $byUinAttribute = 'CUSTOMFIELD:101';

        $response = $this->builder->byUin($uin)->includePaid()->exec();
        $this->assertTrue($this->builder->getAttributes()->hasByType($searchMethod));
        $this->assertTrue($this->builder->getAttributes()->hasByType($searchMethod));
        $this->assertEquals(0, $this->builder->getAttributes()->getByType($searchMethod)->getValue());

        $this->assertTrue($this->builder->getAttributes()->hasByType($byUinAttribute));
        $this->assertEquals('1234,4321', $this->builder->getAttributes()->getByType($byUinAttribute)->getValue());

        $this->assertInstanceOf(FineCollection::class, ($response));
        $this->assertNotEmpty(($response));
        foreach ($response as $fine) {
            $this->assertInstanceOf(Fine::class, $fine);
        }
    }

    public function testBySts()
    {
        $sts            = '1234';
        $searchMethod   = 'CUSTOMFIELD:200';
        $byStsAttribute = 'CUSTOMFIELD:102';

        $response = $this->builder->bySTS($sts)->exec();

        $this->assertTrue($this->builder->getAttributes()->hasByType($searchMethod));
        $this->assertEquals(1, $this->builder->getAttributes()->getByType($searchMethod)->getValue());

        $this->assertTrue($this->builder->getAttributes()->hasByType($byStsAttribute));
        $this->assertEquals($sts, $this->builder->getAttributes()->getByType($byStsAttribute)->getValue());

        $this->assertInstanceOf(FineCollection::class, ($response));
        $this->assertNotEmpty(($response));
        foreach ($response as $fine) {
            $this->assertInstanceOf(Fine::class, $fine);
        }
    }

    public function testByDriverLicense()
    {
        $license                  = '1234';
        $searchMethod             = 'CUSTOMFIELD:200';
        $byDrivelLicenseAttribute = 'CUSTOMFIELD:103';

        $response = $this->builder->byDriverLicense($license)->exec();

        $this->assertTrue($this->builder->getAttributes()->hasByType($searchMethod));
        $this->assertEquals(1, $this->builder->getAttributes()->getByType($searchMethod)->getValue());

        $this->assertTrue($this->builder->getAttributes()->hasByType($byDrivelLicenseAttribute));
        $this->assertEquals(
            $license,
            $this->builder
                ->getAttributes()
                ->getByType($byDrivelLicenseAttribute)
                ->getValue()
        );

        $this->assertInstanceOf(FineCollection::class, ($response));
        $this->assertNotEmpty(($response));
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

        $this->assertTrue($this->builder->getAttributes()->hasByType($dateFromName));
        $this->assertEquals($dateFrom, $this->builder->getAttributes()->getByType($dateFromName)->getValue());

        $this->builder->dateTo($dateTo);

        $this->assertTrue($this->builder->getAttributes()->hasByType($dateToName));
        $this->assertEquals($dateTo, $this->builder->getAttributes()->getByType($dateToName)->getValue());
    }

    public function testBody()
    {
        $this->assertJson($json = $this->builder->byDriverLicense(1234)->toJson());
    }

    public function testNotFound()
    {
        $json = file_get_contents(__DIR__ . '/Mock/EmptyFineResponse.json');
        $this->assertEmpty($response = $this->builder->prepare(json_decode($json)));
        $this->assertInstanceOf(FineCollection::class, ($response));
    }

    public function testException()
    {
        $this->expectException(MonetaBadRequestException::class);
        $this->builder->exec();
    }
}
