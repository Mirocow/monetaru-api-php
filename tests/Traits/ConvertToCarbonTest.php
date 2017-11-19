<?php

namespace AvtoDev\MonetaApi\Tests\Traits;

use Carbon\Carbon;
use PHPUnit\Framework\TestCase;
use AvtoDev\MonetaApi\Tests\Traits\Mock\TraitMock;

/**
 * Class ConvertToCarbonTest.
 *
 * @group traits
 */
class ConvertToCarbonTest extends TestCase
{
    /** @var TraitMock */
    protected $class;

    protected function setUp()
    {
        parent::setUp();
        $this->class = new TraitMock;
    }

    public function testCarbon()
    {
        $this->class->test  = Carbon::create();
        $this->assertEquals($this->class->test, $this->class->toCarbon());
    }

    public function testDateTime()
    {
        $this->class->test  = new \DateTime();
        $carbon             = Carbon::instance($this->class->test);
        $this->assertEquals($carbon, $this->class->toCarbon());
    }

    public function testTimestamp()
    {
        $this->class->test = time();
        $carbon            = Carbon::createFromTimestamp($this->class->test);
        $this->assertEquals($carbon, $this->class->toCarbon());
    }

    public function testString()
    {
        $carbon            = Carbon::now();
        $this->class->test = $carbon->toAtomString();
        $this->assertEquals($carbon, $this->class->toCarbon());
    }

    public function testStringWithFormat()
    {
        $this->class->test       = '01-12-2017';
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
