<?php

namespace AvtoDev\MonetaApi\Tests\HttpClients;

use PHPUnit\Framework\TestCase;
use AvtoDev\MonetaApi\HttpClients\GuzzleHttpClient;
use AvtoDev\MonetaApi\HttpClients\HttpClientInterface;

class GuzzleHttpClientTest extends TestCase
{
    /**
     * @var GuzzleHttpClient
     */
    protected $client;

    protected function setUp()
    {
        parent::setUp();
        $this->client = new GuzzleHttpClient([]);
    }

    public function testInterface()
    {
        $this->assertInstanceOf(HttpClientInterface::class, $this->client);
    }
}
