<?php

namespace AvtoDev\MonetaApi\Tests\Support;

use PHPUnit\Framework\TestCase;
use AvtoDev\MonetaApi\Support\AttributeCollection;
use AvtoDev\MonetaApi\Types\Attributes\MonetaAttribute;

/**
 * Class AttributeCollectionTest.
 *
 * @group cur
 */
class AttributeCollectionTest extends TestCase
{
    /**
     * @var AttributeCollection
     */
    protected $attributes;

    protected function setUp()
    {
        parent::setUp();
        $this->attributes = new AttributeCollection;
    }

    public function testClear()
    {
        $this->attributes->push(new MonetaAttribute('key', 'value'));
        $this->attributes->clear();
        $this->assertTrue($this->attributes->isEmpty());
    }

    public function testCopy()
    {
        $this->attributes->push(new MonetaAttribute('key', 'value'));
        $this->assertEquals($this->attributes, $attributesCollection = $this->attributes->copy());
        $this->attributes->clear();
        $this->assertNotEquals($this->attributes, $attributesCollection);
    }

    public function testIsEmpty()
    {
        $this->assertTrue($this->attributes->isEmpty());
        $this->attributes->push(new MonetaAttribute('key', 'value'));
        $this->assertFalse($this->attributes->isEmpty());
        $this->attributes->clear();
        $this->assertTrue($this->attributes->isEmpty());
    }

    public function testCount()
    {
        $this->assertEquals(0, $this->attributes->count());
        $this->assertCount(0, $this->attributes);

        $this->attributes->push(new MonetaAttribute('key', 'value'));

        $this->assertEquals(1, $this->attributes->count());
        $this->assertCount(1, $this->attributes);
    }

    public function testToArray()
    {
        $this->attributes->push($attribute = new MonetaAttribute('key', 'value'));

        $this->assertEquals(['key' => $attribute], $this->attributes->toArray());
    }

    public function testRewind()
    {
        $this->prepare();
        $this->attributes->next();
        $this->attributes->next();
        $this->attributes->rewind();
        $this->assertEquals('key', $this->attributes->key());
    }

    public function testCurrent()
    {
        $this->attributes->push($attribute1 = new MonetaAttribute('key', 'value'));
        $this->attributes->push($attribute2 = new MonetaAttribute('key2', 'value'));
        $this->attributes->push($attribute3 = new MonetaAttribute('key3', 'value'));

        $this->assertEquals($attribute1, $this->attributes->current());
        $this->attributes->next();
        $this->assertEquals($attribute2, $this->attributes->current());
        $this->attributes->next();
        $this->assertEquals($attribute3, $this->attributes->current());
    }

    public function testKey()
    {
        $this->prepare();

        $this->assertEquals('key', $this->attributes->key());
        $this->attributes->next();
        $this->assertEquals('key2', $this->attributes->key());
        $this->attributes->next();
        $this->assertEquals('key3', $this->attributes->key());
    }

    public function testValid()
    {
        $this->prepare();
        $this->assertTrue($this->attributes->valid());
        $this->attributes->next();
        $this->assertTrue($this->attributes->valid());
        $this->attributes->next();
        $this->assertTrue($this->attributes->valid());
        $this->attributes->next();
        $this->assertFalse($this->attributes->valid());
    }

    public function testJsonSerialize()
    {
        $this->prepare();
        $this->assertEquals(
            [
                'key'  => 'value',
                'key2' => 'value',
                'key3' => 'value',
            ],
            $this->attributes->jsonSerialize()
        );
    }

    public function testHas()
    {
        $this->assertFalse($this->attributes->has($attribute = new MonetaAttribute('key', 'value')));
        $this->attributes->push($attribute);
        $this->assertTrue($this->attributes->has($attribute));
    }

    public function testHasByType()
    {
        $attribute = new MonetaAttribute('key', 'value');
        $this->assertFalse($this->attributes->hasByType($attribute->getName()));
        $this->attributes->push($attribute);
        $this->assertTrue($this->attributes->hasByType($attribute->getName()));
    }

    public function testHasByValue()
    {
        $attribute = new MonetaAttribute('key', 'value');
        $this->assertFalse($this->attributes->hasByValue($attribute->getValue()));
        $this->attributes->push($attribute);
        $this->assertTrue($this->attributes->hasByValue($attribute->getValue()));
    }

    public function testGetByType()
    {
        $attribute = new MonetaAttribute('key', 'value');
        $this->assertNull($this->attributes->getByType($attribute->getName()));
        $this->attributes->push($attribute);
        $this->assertEquals($attribute, $this->attributes->getByType($attribute->getName()));
    }

    public function testDrop()
    {
        $attribute = new MonetaAttribute('key', 'value');
        $this->attributes->push($attribute);
        $this->assertEquals($attribute, $this->attributes->getByType($attribute->getName()));
        $this->attributes->drop($attribute->getName());
        $this->assertNull($this->attributes->getByType($attribute->getName()));
    }

    protected function prepare()
    {
        $this->attributes->push(new MonetaAttribute('key', 'value'));
        $this->attributes->push(new MonetaAttribute('key2', 'value'));
        $this->attributes->push(new MonetaAttribute('key3', 'value'));
    }
}
