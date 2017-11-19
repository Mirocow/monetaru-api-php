<?php

namespace AvtoDev\MonetaApi\Tests\Traits;

use PHPUnit\Framework\TestCase;
use AvtoDev\MonetaApi\Tests\Traits\Mock\TraitMock;

/**
 * Class ConvertToArrayTest.
 *
 * @group traits
 */
class ConvertToArrayTest extends TestCase
{
    /** @var TraitMock */
    protected $class;

    protected function setUp()
    {
        parent::setUp();
        $this->class = new TraitMock;
    }

    public function testArray()
    {
        $this->class->test = $array = ['key' => 'val'];
        $this->assertEquals($array, $this->class->toArray());
    }

    public function testJson()
    {
        $this->class->test = $json = '{"key":"value"}';
        $this->assertEquals(['key' => 'value'], $this->class->toArray());
    }

    public function testObject()
    {
        $this->class->test       = clone $this->class;
        $this->class->test->test = $json = '{"key":"value"}';
        $this->assertEquals(['key' => 'value'], $this->class->toArray());
    }

    public function testOther()
    {
        $test              = new \stdClass();
        $test->key         = 'value';
        $this->class->test = $test;
        $this->assertEquals(['key' => 'value'], $this->class->toArray());
    }
}
