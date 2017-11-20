<?php

namespace AvtoDev\MonetaApi\Tests\Types;

use PHPUnit\Framework\TestCase;
use AvtoDev\MonetaApi\Types\Provider;

class ProviderTest extends TestCase
{
    /**
     * @var Provider;
     */
    protected $fine;

    protected $json;

    protected function setUp()
    {
        $this->fine = \Mockery::mock(Provider::class)->shouldAllowMockingProtectedMethods()->makePartial();
        $this->json = file_get_contents(__DIR__ . '/Mock/ProviderExample.json');
        $this->fine->configure($this->json);
    }

    public function testGeters()
    {
        $this->assertNotNull($this->fine->getId());
        $this->assertNotNull($this->fine->getSubProviderId());
        $this->assertNotNull($this->fine->getName());
        $this->assertNotNull($this->fine->gettargetAccountId());
    }
}
