<?php

namespace AvtoDev\MonetaApi\Tests\Types\Attributes;

use PHPUnit\Framework\TestCase;
use AvtoDev\MonetaApi\Types\Attributes\MonetaAttribute;

class AbstractAttributeTest extends TestCase
{
    /**
     * @var MonetaAttribute
     */
    protected $attribute;

    protected function setUp()
    {
        parent::setUp();
        $this->attribute = new MonetaAttribute('key', 'value');
    }

    public function testToArray()
    {
        $this->assertEquals(['key' => 'value'], $this->attribute->toArray());
    }

    public function testToJson()
    {
        $this->assertJson('{"key":"value"}', $this->attribute->toJson());
    }

    public function testGetters()
    {
        $this->assertEquals('key', $this->attribute->getName());
        $this->assertEquals('value', $this->attribute->getValue());
    }
}
