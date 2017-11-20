<?php

namespace AvtoDev\MonetaApi\Tests\Traits;

class StackValuesDotAccessibleTest extends AbstractTraitTestCase
{
    protected function setUp()
    {
        parent::setUp();
        $this->class->config = [
            'first' => [
                'second' => 'value',
            ],
        ];
    }

    public function testAccess()
    {
        $this->assertEquals('value', $this->class->getStackValueWithDot('first.second'));
    }

    public function testDefault()
    {
        $this->assertEquals('default', $this->class->getStackValueWithDot('none', 'default'));
    }

    public function testArray()
    {
        $this->assertEquals(['second' => 'value'], $this->class->getStackValueWithDot('first'));
    }
}
