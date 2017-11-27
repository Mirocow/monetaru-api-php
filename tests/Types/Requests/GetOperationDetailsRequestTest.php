<?php

namespace AvtoDev\MonetaApi\Tests\Types\Requests;

use AvtoDev\MonetaApi\Types\OperationDetails;
use AvtoDev\MonetaApi\Types\Requests\Payments\GetOperationDetailsRequest;

class GetOperationDetailsRequestTest extends AbstractRequestTestCase
{
    /**
     * @var GetOperationDetailsRequest
     */
    protected $request;

    protected function setUp()
    {
        parent::setUp();
        $this->request = $this->api->payments()->getOperationDetails();
    }

    public function testById()
    {
        $json = file_get_contents(__DIR__ . '/Mock/GetOperationDetailsRequestExample.json');
        $this->assertJsonStringEqualsJsonString(
            $json,
            $this->request
                ->byId('123456789')
                ->toJson()
        );
    }

    public function testExec()
    {
        $this->assertInstanceOf(OperationDetails::class, $this->request->byId('123456789')->exec());
    }
}
