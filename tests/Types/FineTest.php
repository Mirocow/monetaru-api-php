<?php

namespace AvtoDev\MonetaApi\Tests\Types;

use PHPUnit\Framework\TestCase;
use AvtoDev\MonetaApi\Types\Fine;
use AvtoDev\MonetaApi\Types\Attributes\MonetaAttribute;

/**
 * Class FineTest.
 *
 * @group types
 */
class FineTest extends TestCase
{
    /**
     * @var Fine;
     */
    protected $fine;

    protected $json;

    protected function setUp()
    {
        $this->json = file_get_contents(__DIR__ . '/Mock/FineExample.json');
        $this->fine = new Fine($this->json);
    }

    public function testOperationInfo()
    {
        /** @var MonetaAttribute $attribute */
        foreach ($this->fine->getOperationInfo() as $attribute) {
            $this->assertInstanceOf(MonetaAttribute::class, $attribute);
        }
    }

    public function testGeters()
    {
        $this->assertNotNull($this->fine->getId());
        $this->assertNotNull($this->fine->getAmount());
        $this->assertNotNull($this->fine->getLabel());
        $this->assertNotNull($this->fine->getBillDate());
        $this->assertNotNull($this->fine->getSoiName());
        $this->assertNotNull($this->fine->getWireUserInn());
        $this->assertNotNull($this->fine->getWireKpp());
        $this->assertNotNull($this->fine->getWireBankAccount());
        $this->assertNotNull($this->fine->getWireBankName());
        $this->assertNotNull($this->fine->getWireBankBik());
        $this->assertNotNull($this->fine->getWirePaymentPurpose());
        $this->assertNotNull($this->fine->getWireUsername());
        $this->assertNotNull($this->fine->getWireKbk());
        $this->assertNotNull($this->fine->getWireOktmo());
        $this->assertNotNull($this->fine->getWireAltPayerIdentifier());
        $this->assertNotNull($this->fine->getSign());
        $this->assertNotNull($this->fine->getTotalAmount());
        $this->assertNotNull($this->fine->getIsPaid());
        $this->assertNotNull($this->fine->getDiscountSize());
        $this->assertNotNull($this->fine->getDiscountDate());
    }
}
