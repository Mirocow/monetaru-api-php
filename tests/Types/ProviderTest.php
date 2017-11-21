<?php

namespace AvtoDev\MonetaApi\Tests\Types;

use PHPUnit\Framework\TestCase;
use AvtoDev\MonetaApi\Types\Provider;

/**
 * Class ProviderTest.
 *
 * @group types
 */
class ProviderTest extends TestCase
{
    /**
     * @var Provider;
     */
    protected $provider;

    protected $json;

    protected function setUp()
    {
        $this->json     = file_get_contents(__DIR__ . '/Mock/ProviderExample.json');
        $this->provider = new Provider($this->json);
    }

    public function testGeters()
    {
        $this->assertNotNull($this->provider->getId());
        $this->assertNotNull($this->provider->getSubProviderId());
        $this->assertNotNull($this->provider->getName());
        $this->assertNotNull($this->provider->gettargetAccountId());
    }
}
