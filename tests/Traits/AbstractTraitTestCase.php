<?php

namespace AvtoDev\MonetaApi\Tests\Traits;

use PHPUnit\Framework\TestCase;
use AvtoDev\MonetaApi\Tests\Traits\Mock\TraitMock;

class AbstractTraitTestCase extends TestCase
{
    /** @var TraitMock */
    protected $class;

    protected function setUp()
    {
        parent::setUp();
        $this->class = new TraitMock;
    }
}
