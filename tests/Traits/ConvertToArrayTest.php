<?php

namespace AvtoDev\MonetaApi\Tests\Traits;

use AvtoDev\MonetaApi\Tests\Traits\Mock\TraitMock;

/**
 * Class ConvertToArrayTest.
 *
 * @group traits
 */
class ConvertToArrayTest extends AbstractTraitTestCase
{
    public function testArray()
    {
        $this->class->setTest($array = ['key' => 'val']);
        $this->assertEquals($array, $this->class->toArray());
    }

    public function testJson()
    {
        $this->class->setTest('{"key":"value"}');
        $this->assertEquals(['key' => 'value'], $this->class->toArray());
    }

    public function testObject()
    {
        $test       = new TraitMock;
        $test->test = ['key' => 'value'];

        $this->class->setTest($test);
        $this->assertEquals(['key' => 'value'], $this->class->toArray());
    }

    public function testOther()
    {
        $test      = new \stdClass;
        $test->key = 'value';
        $this->class->setTest($test);
        $this->assertEquals(['key' => 'value'], $this->class->toArray());
    }
}
