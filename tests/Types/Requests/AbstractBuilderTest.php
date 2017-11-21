<?php

namespace AvtoDev\MonetaApi\Tests\Types\Requests;

use Mockery\MockInterface;
use AvtoDev\MonetaApi\Tests\Types\Requests\Mock\RequestMock;

/**
 * Class AbstractBuilderTest.
 *
 * @group requests
 * @group types
 */
class AbstractBuilderTest extends AbstractRequestTestCase
{
    /**
     * @var MockInterface|RequestMock
     */
    protected $builder;

    protected function setUp()
    {
        parent::setUp();

        $this->builder = new RequestMock($this->api);
    }

    public function testTest()
    {
        $this->assertInstanceOf(\stdClass::class, $this->builder->setTest('value')->exec());
    }
}
