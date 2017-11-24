<?php

namespace AvtoDev\MonetaApi\Tests\Types;

use PHPUnit\Framework\TestCase;
use AvtoDev\MonetaApi\Support\AttributeCollection;
use AvtoDev\MonetaApi\Types\Attributes\MonetaAttribute;
use AvtoDev\MonetaApi\Tests\Types\Mock\AbstractTypeMock;

class AbstractTypeTest extends TestCase
{
    /**
     * @var AbstractTypeMock
     */
    protected $type;

    protected $config = [
        'test' => 'value',
    ];

    public function setUp()
    {
        parent::setUp();
        $this->type = new AbstractTypeMock($this->config);
    }

    public function testToArray()
    {
        $this->assertEquals($this->config, $this->type->toArray());
    }

    public function testGetAttributes()
    {
        $this->assertInstanceOf(AttributeCollection::class, $attributeCollection = $this->type->getAttributes());
        $collection = new AttributeCollection;
        foreach ($this->config as $key => $value) {
            $collection->push(new MonetaAttribute($key, $value));
        }
        $this->assertEquals($collection, $attributeCollection);
    }
}
