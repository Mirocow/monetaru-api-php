<?php

namespace AvtoDev\MonetaApi\Tests\Traits;

use Carbon\Carbon;

/**
 * Class ConvertToCarbonTest.
 *
 * @group traits
 */
class ConvertToCarbonTest extends AbstractTraitTestCase
{
    public function testCarbon()
    {
        $this->class->setTest(Carbon::create());
        $this->assertEquals($this->class->test, $this->class->toCarbon());
    }

    public function testDateTime()
    {
        $this->class->setTest(new \DateTime);
        $carbon = Carbon::instance($this->class->test);
        $this->assertEquals($carbon, $this->class->toCarbon());
    }

    public function testTimestamp()
    {
        $this->class->setTest(time());
        $carbon = Carbon::createFromTimestamp($this->class->test);
        $this->assertEquals($carbon, $this->class->toCarbon());
    }

    public function testString()
    {
        $carbon = Carbon::create();
        $this->class->setTest($carbon->toAtomString());
        $this->assertEquals($carbon, $this->class->toCarbon());
    }

    public function testStringWithFormat()
    {
        $this->class->setTest('01-12-2017');
        $this->class->dateFormat = 'd-m-Y';
        $carbon                  = Carbon::create(2017, 12, 01);
        $this->assertEquals($carbon, $this->class->toCarbon());
    }

    public function testOtherCase()
    {
        $this->class->test = new \stdClass;
        $this->assertNull($this->class->toCarbon());
    }
}
