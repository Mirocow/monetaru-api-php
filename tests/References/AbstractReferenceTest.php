<?php

namespace AvtoDev\MonetaApi\Tests\References;

use PHPUnit\Framework\TestCase;
use AvtoDev\MonetaApi\Tests\References\Mock\ReferenceMock;

class AbstractReferenceTest extends TestCase
{
    protected $constants = [1, 2];

    /**
     * @var ReferenceMock
     */
    protected $reference;

    protected function setUp()
    {
        parent::setUp();
        $this->reference = new ReferenceMock;
    }

    public function testHas()
    {
        $this->assertTrue(ReferenceMock::has(1));
        $this->assertTrue(ReferenceMock::has(2));
        $this->assertFalse(ReferenceMock::has(3));
    }

    public function testToArray()
    {
        $this->assertEquals($this->constants, $this->reference->toArray());
    }

    public function testToJson()
    {
        $this->assertEquals(\json_encode($this->constants), $this->reference->toJson());
    }
}
