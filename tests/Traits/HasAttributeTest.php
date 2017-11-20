<?php

namespace AvtoDev\MonetaApi\Tests\Traits;

use AvtoDev\MonetaApi\Types\Attributes\MonetaAttribute;

/**
 * Class HasAttributeTest.
 *
 * @group traits
 */
class HasAttributeTest extends AbstractTraitTestCase
{
    public function testAttributeSet()
    {
        $this->assertFalse($this->class->hasAttributeByValue('value'));

        $this->class->pushAttribute($attribute = new MonetaAttribute('test', 'value'));
        $this->assertTrue($this->class->hasAttribute($attribute));
        $this->assertTrue($this->class->hasAttributeByType('test'));
        $this->assertTrue($this->class->hasAttributeByValue('value'));
        $this->assertCount(1, $this->class->attributes());
    }

    public function testAttributeGet()
    {
        $this->assertNull($this->class->getAttributeByType('test'));

        $this->class->pushAttribute($attribute = new MonetaAttribute('test', 'value'));

        $this->assertInstanceOf(MonetaAttribute::class, $getAttribute = $this->class->getAttributeByType('test'));
        $this->assertEquals($attribute, $getAttribute);
        $this->assertEquals('value', $getAttribute->getValue());
        $this->assertEquals('test', $getAttribute->getName());
    }

    public function testAttributeClear()
    {
        $this->class->pushAttribute($attribute = new MonetaAttribute('test', 'value'));
        $this->class->clearAttributes();
        $this->assertCount(0, $this->class->attributes());

        $this->class->pushAttribute($attribute = new MonetaAttribute('test', 'value'));
        $this->class->dropAttribute($attribute->getName());
        $this->assertCount(0, $this->class->attributes());
    }
}
