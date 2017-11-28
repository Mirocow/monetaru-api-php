<?php

namespace AvtoDev\MonetaApi\Tests;

use PHPUnit\Framework\TestCase;
use AvtoDev\MonetaApi\Types\Fine;
use AvtoDev\MonetaApi\Types\Invoice;
use AvtoDev\MonetaApi\Types\Payment;
use AvtoDev\MonetaApi\Clients\MonetaApi;
use AvtoDev\MonetaApi\Support\FineCollection;
use AvtoDev\MonetaApi\Types\OperationDetails;
use AvtoDev\MonetaApi\Types\Requests\FinesRequest;
use AvtoDev\MonetaApi\Clients\ApiCommands\FinesApiCommands;
use AvtoDev\MonetaApi\Types\Requests\Payments\InvoiceRequest;
use AvtoDev\MonetaApi\Types\Requests\Payments\PaymentRequest;
use AvtoDev\MonetaApi\Clients\ApiCommands\PaymentsApiCommands;
use AvtoDev\MonetaApi\Types\Requests\Payments\GetOperationDetailsRequest;

/**
 * Class FeatureTest.
 *
 * Тест описывает работу с Api МОНЕТА.РУ
 *
 * @group feature
 */
class FeatureTest extends TestCase
{
    /**
     * @var MonetaApi
     */
    protected $api;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        //обязательные настройки
        $config = [
            'authorization' => [
                'username' => 'username',
                'password' => 'password',
            ],
            'accounts'      => [
                'fines'      => [
                    'id'       => '123456789',
                    'password' => '555',
                ],
                'commission' => [
                    'id' => '987654321',
                ],
            ],
            'is_test'       => true,
        ];

        $this->api = new MonetaApi($config);
    }

    /**
     * Получаем штрафы.
     */
    public function testFindFines()
    {
        $this->assertInstanceOf(
            FinesApiCommands::class,
            $finesApi = $this->api
                ->fines()
        );
        $this->assertInstanceOf(
            FinesRequest::class,
            $request = $finesApi
                ->find()
        );

        $this->assertInstanceOf(
            FineCollection::class,
            $fines = $request
                ->bySTS('123456789')//Поиск по СТС
                ->includePaid()//Включая оплаченные
                ->exec()//Выполнить
        );
        $this->assertCount(2, $fines);
        $this->assertEquals(2, $fines->count());
        $amountSum = 0;
        foreach ($fines as $fine) {
            $this->assertInstanceOf(Fine::class, $fine);
            $this->assertEquals(800, $amount = $fine->getAmount());
            $amountSum += $amount;
        }
        $this->assertEquals($amountSum, $fines->totalAmount());
    }

    /**
     * Запрашиваем деньги от пользователя.
     */
    public function testInvoice()
    {
        $this->assertInstanceOf(
            PaymentsApiCommands::class,
            $paymentsApi = $this->api->payments()
        );
        $this->assertInstanceOf(
            InvoiceRequest::class,
            $request = $paymentsApi->invoice()
        );

        $this->assertInstanceOf(
            Invoice::class,
            $invoice = $request
                ->setDestinationAccount(
                    $this->api->getConfigValue('accounts.fines.id')
                )// Номер счета на который должны прийти деньги
                ->setClientTransactionId('operationId')//Номер транзакции в системе клиента
                ->setAmount(900)
                ->exec()
        );
        $this->assertEquals(123456789, $transactionId = $invoice->getTransactionId());
        $url = 'https://www.payanyway.ru/assistant.htm?operationId='
               . $transactionId
               . '&paymentSystem.unitId=card&paymentSystem.limitIds=card&followup=true';
        $this->assertEquals(
            $url,
            $invoice->getPaymentUrl()
        );
    }

    /**
     * Проверяем успешность операции.
     */
    public function testOperationDetailsInfo()
    {
        $this->assertInstanceOf(
            GetOperationDetailsRequest::class,
            $request = $this->api->payments()
                ->getOperationDetails()
        );
        $this->assertInstanceOf(
            OperationDetails::class,
            $operationDetails = $request
                ->byId(123456789)
                ->exec()
        );
        $this->assertTrue($operationDetails->isSuccessful());
    }

    /**
     * Оплачиваем штраф.
     */
    public function testPayFine()
    {
        $fines = $this->api->fines()
            ->find()
            ->byUin(['123456789', '987654321'])//По номеру постановления
            ->exec();
        $fine  = $fines->current();
        $this->assertInstanceOf(
            PaymentRequest::class,
            $request = $this->api->payments()->payOne($fine)
        );
        $this->assertInstanceOf(
            Payment::class,
            $payment = $request
                ->setPaymentPassword(
                    $this->api->getConfigValue('accounts.fines.password')
                )//Платёжный пароль счёта
                ->setPayerFio('Тестов Тест Тестович')//Обязательно
                ->setPayerPhone(89292198689)//Обязательно
                ->setParentId(132456789)
                ->setCommission(100)
                ->exec()
        );
        $this->assertTrue($payment->isSuccessful());
    }

    /**
     * Переводим коммисию на другой счет.
     */
    public function testTransfer()
    {
        $this->assertInstanceOf(
            PaymentRequest::class,
            $request = $this->api->payments()->transfer()
        );
        $this->assertInstanceOf(
            Payment::class,
            $payment = $request
                ->setAccountNumber(
                    $this->api->getConfigValue('accounts.fines.id')
                )
                ->setPaymentPassword(
                    $this->api->getConfigValue('accounts.fines.password')
                )//Платёжный пароль счёта
                ->setDestinationAccount(
                    $this->api->getConfigValue('accounts.commission.id')
                )
                ->setAmount(100)
                ->setPayerFio('Тестов Тест Тестович')//Обязательно
                ->setPayerPhone(89292198689)//Обязательно
                ->exec()
        );
        $this->assertTrue($payment->isSuccessful());
    }
}
