<?php

namespace AvtoDev\MonetaApi\Tests\Types;

use Carbon\Carbon;
use AvtoDev\MonetaApi\Types\Invoice;

/**
 * Class InvoiceTest.
 *
 * @group types
 */
class InvoiceTest extends BaseTypeTest
{
    /**
     * @var Invoice
     */
    protected $invoice;

    protected function setUp()
    {
        parent::setUp();
        $this->invoice = $this->api->payments()->invoice()
            ->setDestinationAccount('123')
            ->setClientTransactionId('id9986897')
            ->setAmount(200)
            ->exec();
    }

    public function testIsInvoice()
    {
        $this->assertInstanceOf(Invoice::class, $this->invoice);
    }

    public function testGetters()
    {
        $this->assertEquals(123456789, $this->invoice->getTransactionId());

        $this->assertEquals(
            Carbon::parse('2017-11-24T15:27:53.000+03:00'),
            $this->invoice->getDateTime()
        );

        $this->assertEquals('CREATED', $this->invoice->getStatus());
        $this->assertEquals('id9986897', $this->invoice->getClientTransactionId());

        $url =
            'https://www.payanyway.ru/assistant.htm?'
            . 'operationId=123456789&paymentSystem.unitId=card&'
            . 'paymentSystem.limitIds=card&followup=true';
        $this->assertEquals($url, $this->invoice->getPaymentUrl());
    }
}
