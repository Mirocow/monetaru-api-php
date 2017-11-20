<?php

namespace AvtoDev\MonetaApi\Tests\Traits;

/**
 * Class ConvertToArrayTest.
 *
 * @group traits
 */
class ConvertToArrayTest extends AbstractTraitTestCase
{
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
        $test              = new \stdClass;
        $test->key         = 'value';
        $this->class->test = $test;
        $this->assertEquals(['key' => 'value'], $this->class->toArray());
    }
}
