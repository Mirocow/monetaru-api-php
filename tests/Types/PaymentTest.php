<?php

namespace AvtoDev\MonetaApi\Tests\Types;

use AvtoDev\MonetaApi\Types\Payment;

/**
 * Class PaymentTest.
 *
 * @group types
 */
class PaymentTest extends BaseTypeTest
{
    /**
     * @var Payment
     */
    protected $payment;

    protected function setUp()
    {
        parent::setUp();
        $this->payment = $this->api->payments()
            ->transfer()
            ->setDestinationAccount('')
            ->setAmount(10)
            ->setAccountNumber('')
            ->exec();
    }

    public function testStatus()
    {
        $this->assertTrue($this->payment->isSuccessful());
    }
}
