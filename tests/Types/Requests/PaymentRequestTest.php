<?php

namespace AvtoDev\MonetaApi\Tests\Types\Requests;

use AvtoDev\MonetaApi\Types\Fine;
use AvtoDev\MonetaApi\Types\Payment;
use AvtoDev\MonetaApi\References\PaymentRequestReference;
use AvtoDev\MonetaApi\Types\Requests\Payments\PaymentRequest;

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
        $builder       = $this->api->payments()->payOne($fine);
        $this->builder = \Mockery::mock($builder);
    }

    public function testSetPaymentPassword()
    {
        $this->builder->setPaymentPassword($password = '123456789');
        $this->assertEquals($password,
            $this->builder->getAttributes()->getByType(PaymentRequestReference::FIELD_PAYMENT_PASSWORD)->getValue());
    }

    public function testCreate()
    {
        $json = file_get_contents(__DIR__ . '/Mock/PaymentRequestExample.json');

        $this->assertJsonStringEqualsJsonString($json,
            $this->builder->setPayerPhone(89876543210)->setPayerFio('Некто с именем')->toJson());
    }

    public function testExec()
    {
        $this->assertInstanceOf(Payment::class, $payment =
            $this->builder->setPayerPhone(89876543210)->setPayerFio('Некто с именем')->exec());
        $this->assertEquals(481587, $payment->getId());
    }
}
