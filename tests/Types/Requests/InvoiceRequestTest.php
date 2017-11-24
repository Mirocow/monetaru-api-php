<?php

namespace AvtoDev\MonetaApi\Tests\Types\Requests;

use Mockery;
use Mockery\MockInterface;
use AvtoDev\MonetaApi\Types\Invoice;
use AvtoDev\MonetaApi\References\InvoiceRequestReference;
use AvtoDev\MonetaApi\Exceptions\MonetaBadRequestException;
use AvtoDev\MonetaApi\Types\Requests\Payments\InvoiceRequest;

class InvoiceRequestTest extends AbstractRequestTestCase
{
    /**
     * @var InvoiceRequest|MockInterface
     */
    protected $builder;

    protected function setUp()
    {
        parent::setUp();
        $mock          = new InvoiceRequest($this->api);
        $this->builder = Mockery::mock($mock)->shouldAllowMockingProtectedMethods()->makePartial();
    }

    public function testSetAmount()
    {
        $amount = 200;
        $this->assertEquals(
            $amount,
            $this->builder
                ->setAmount($amount)
                ->getAttributes()
                ->getByType('amount')
                ->getValue()
        );
    }

    public function testSetDestinationAccount()
    {
        $accountNumber = '123456789';
        $this->assertEquals(
            $accountNumber,
            $this->builder
                ->setDestinationAccount($accountNumber)
                ->getAttributes()
                ->getByType('payee')
                ->getValue()
        );
    }

    public function testRequestPaymentToken()
    {
        $this->builder->requestPaymentToken();
        $this->assertEquals(
            'request',
            $this->builder
                ->getOperationInfo()
                ->getByType('PAYMENTTOKEN')
                ->getValue()
        );

        $this->assertEquals(
            $this->api->getConfigValue('accounts.payer_card'),
            $this->builder
                ->getAttributes()
                ->getByType('payer')
                ->getValue()
        );
    }

    public function testSetClientTransactionId()
    {
        $clientTransactionId = 'avTest1';
        $this->assertEquals(
            $clientTransactionId,
            $this->builder
                ->setClientTransactionId($clientTransactionId)
                ->getAttributes()
                ->getByType('clientTransaction')
                ->getValue()
        );
    }

    public function testAmountRequired()
    {
        $this->expectException(MonetaBadRequestException::class);
        $this->expectExceptionMessage(
            'Не заполнен обязательный атрибут: ' .
            InvoiceRequestReference::FIELD_AMOUNT
            . '. Обработка невозможна. Не найдены необходимые объекты'
        );
        $this->builder->exec();
    }

    public function testDestinationAccountRequired()
    {
        $this->expectException(MonetaBadRequestException::class);
        $this->expectExceptionMessage(
            'Не заполнен обязательный атрибут: ' .
            InvoiceRequestReference::FIELD_PAYEE
            . '. Обработка невозможна. Не найдены необходимые объекты'
        );
        $this->builder->setAmount(200)->exec();
    }

    public function testClientTransactionIdRequired()
    {
        $this->expectException(MonetaBadRequestException::class);
        $this->expectExceptionMessage(
            'Не заполнен обязательный атрибут: ' .
            InvoiceRequestReference::FIELD_CLIENT_TRANSACTION_ID
            . '. Обработка невозможна. Не найдены необходимые объекты'
        );
        $this->builder->setAmount(200)->setDestinationAccount('123')->exec();
    }

    public function testExec()
    {
        $this->assertInstanceOf(
            Invoice::class,
            $this->builder
                ->setAmount(200)
                ->setDestinationAccount('123')
                ->setClientTransactionId('avTest1')
                ->exec()
        );
    }

    public function testToJson()
    {
        $json = file_get_contents(__DIR__ . '/Mock/InvoiceRequestExample.json');
        $this->assertJsonStringEqualsJsonString(
            $json,
            $this->builder
                ->setAmount(200)
                ->setDestinationAccount('123')
                ->setClientTransactionId('avTest1')
                ->requestPaymentToken()
                ->toJson()
        );
    }
}
