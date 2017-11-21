<?php

namespace AvtoDev\MonetaApi\Tests\Types\Requests;

use AvtoDev\MonetaApi\Types\Fine;
use AvtoDev\MonetaApi\Types\Requests\PaymentRequest;

/**
 * Class PaymentRequestTest.
 *
 * @group requests
 * @group types
 */
class PaymentRequestTest extends AbstractRequestTestCase
{
    /** @var PaymentRequest */
    protected $builder;

    protected function setUp()
    {
        parent::setUp();
        $json          = file_get_contents(realpath('tests/Types/Mock/FineExample.json'));
        $fine          = new Fine($json);
        $builder       = new PaymentRequest($this->api, $fine);
        $this->builder = \Mockery::mock($builder);
    }

    public function testCreate()
    {
        $json = file_get_contents(__DIR__ . '/Mock/PaymentRequestExample.json');

        $this->assertJsonStringEqualsJsonString($json,
            $this->builder->setPayerPhone(89876543210)->setPayerFio('Некто с именем')->toJson());
    }
}
