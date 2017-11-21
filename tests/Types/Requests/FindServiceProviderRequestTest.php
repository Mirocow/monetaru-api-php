<?php

namespace AvtoDev\MonetaApi\Tests\Types\Requests;

use AvtoDev\MonetaApi\Types\Provider;
use AvtoDev\MonetaApi\Types\Attributes\MonetaAttribute;
use AvtoDev\MonetaApi\Types\Requests\FindServiceProviderByIdRequest;

/**
 * Class FindServiceProviderRequestTest.
 *
 * @group requests
 * @group types
 */
class FindServiceProviderRequestTest extends AbstractRequestTestCase
{
    /**
     * @var FindServiceProviderByIdRequest
     */
    protected $builder;

    protected function setUp()
    {
        parent::setUp();
        $this->builder = new FindServiceProviderByIdRequest($this->api);
    }

    public function testById()
    {
        $id           = '123';
        $providerType = 'providerId';
        $return       = $this->builder->byId($id)->exec();
        $this->assertInstanceOf(Provider::class, $return);
        $this->assertInstanceOf(MonetaAttribute::class, $this->builder->getAttributes()->getByType($providerType));
        $this->assertEquals($id, $this->builder->getAttributes()->getByType($providerType)->getValue());
    }

    public function testToJson()
    {
        $this->assertJson($this->builder->byId('123')->toJson());
    }
}
