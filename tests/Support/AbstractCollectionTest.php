<?php

namespace AvtoDev\MonetaApi\Tests\Support;

use PHPUnit\Framework\TestCase;
use AvtoDev\MonetaApi\Tests\Support\Mock\MockCollection;

/**
 * Class AttributeCollectionTest.
 *
 * @group cur
 */
class AbstractCollectionTest extends TestCase
{
    /**
     * @var MockCollection
     */
    protected $attributes;

    protected function setUp()
    {
        parent::setUp();
        $this->attributes = new MockCollection;
    }

    public function testClear()
    {
        $this->attributes->push('value');
        $this->attributes->clear();
        $this->assertTrue($this->attributes->isEmpty());
    }

    public function testCopy()
    {
        $this->attributes->push('value');
        $this->assertEquals($this->attributes, $attributesCollection = $this->attributes->copy());
        $this->attributes->clear();
        $this->assertNotEquals($this->attributes, $attributesCollection);
    }

    public function testIsEmpty()
    {
        $this->assertTrue($this->attributes->isEmpty());
        $this->attributes->push('value');
        $this->assertFalse($this->attributes->isEmpty());
        $this->attributes->clear();
        $this->assertTrue($this->attributes->isEmpty());
    }

    public function testCount()
    {
        $this->assertEquals(0, $this->attributes->count());
        $this->assertCount(0, $this->attributes);

        $this->attributes->push('value');

        $this->assertEquals(1, $this->attributes->count());
        $this->assertCount(1, $this->attributes);
    }

    public function testToArray()
    {
        $this->attributes->push($attribute = 'value');

        $this->assertEquals([$attribute], $this->attributes->toArray());
    }

    public function testRewind()
    {
        $this->prepare();
        $this->attributes->next();
        $this->attributes->next();
        $this->attributes->rewind();
        $this->assertEquals(0, $this->attributes->key());
    }

    public function testCurrent()
    {
        $this->attributes->push($attribute1 = 'value');
        $this->attributes->push($attribute2 = 'value2');
        $this->attributes->push($attribute3 = 'value3');

        $this->assertEquals($attribute1, $this->attributes->current());
        $this->attributes->next();
        $this->assertEquals($attribute2, $this->attributes->current());
        $this->attributes->next();
        $this->assertEquals($attribute3, $this->attributes->current());
    }

    public function testKey()
    {
        $this->prepare();

        $this->assertEquals(0, $this->attributes->key());
        $this->attributes->next();
        $this->assertEquals(1, $this->attributes->key());
        $this->attributes->next();
        $this->assertEquals(2, $this->attributes->key());
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

    public function testDrop()
    {
        $attribute = 'value';
        $this->attributes->push($attribute);
        $this->attributes->push('value2');
        $this->attributes->drop(0);
        $this->assertCount(1, $this->attributes);
    }

    protected function prepare()
    {
        $this->attributes->push('value');
        $this->attributes->push('value2');
        $this->attributes->push('value3');
    }
}
