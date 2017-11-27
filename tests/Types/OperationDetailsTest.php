<?php

namespace AvtoDev\MonetaApi\Tests\Types;

use AvtoDev\MonetaApi\Types\OperationDetails;

class OperationDetailsTest extends BaseTypeTest
{
    /**
     * @var OperationDetails
     */
    protected $operation;

    protected function setUp()
    {
        parent::setUp();
        $this->operation = $this->api->payments()->getOperationDetails()->byId('123456789')->exec();
    }

    public function testGetters()
    {
        $this->assertEquals(123456789, $this->operation->getId());
        $this->assertEquals(10, $this->operation->getSourceAmount());
        $this->assertEquals(10, $this->operation->getSourceAmountTotal());
        $this->assertEquals('CREATED', $this->operation->getStatusId());
    }
}
