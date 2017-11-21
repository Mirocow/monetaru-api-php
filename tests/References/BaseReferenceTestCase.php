<?php

namespace AvtoDev\MonetaApi\Tests\References;

use PHPUnit\Framework\TestCase;

abstract class BaseReferenceTestCase extends TestCase
{
    protected $constants;

    protected function setUp()
    {
        parent::setUp();
        sort($this->constants);
    }

    public function testGetAll()
    {
        $all = $this->getAll();
        sort($all);
        $this->assertEquals($this->constants, $all);
    }

    /**
     * @return array
     */
    abstract protected function getAll();
}
