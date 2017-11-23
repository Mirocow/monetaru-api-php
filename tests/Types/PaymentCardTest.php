<?php

namespace AvtoDev\MonetaApi\Tests\Types;

use Carbon\Carbon;
use PHPUnit\Framework\TestCase;
use AvtoDev\MonetaApi\Types\PaymentCard;
use AvtoDev\MonetaApi\Exceptions\MonetaBadRequestException;

class PaymentCardTest extends TestCase
{
    /**
     * @var PaymentCard
     */
    protected $card;

    protected function setUp()
    {
        parent::setUp();
        $this->card = new PaymentCard;
    }

    public function testConfigure()
    {
        $config = [
            'CARDNUMBER'     => '4444 4444 4444 4448',
            'CARDEXPIRATION' => '2020-02',
            'CARDCVV2'       => '021',
        ];
        $this->card->configure($config);

        $card = [
            [
                'key'   => 'CARDNUMBER',
                'value' => '4444444444444448',
            ],
            [
                'key'   => 'CARDEXPIRATION',
                'value' => '02/2020',
            ],
            [
                'key'   => 'CARDCVV2',
                'value' => '021',
            ],
        ];
        $this->assertEquals($card, $this->card->toArray());
    }

    public function testExceptionCardNumber()
    {
        $this->expectException(MonetaBadRequestException::class);
        $this->expectExceptionMessage('Некорректный формат поля "CARDNUMBER"');
        $this->card->setNumber('');
    }

    public function testExceptionCardExpiration()
    {
        $this->expectException(MonetaBadRequestException::class);
        $this->expectExceptionMessage('Срок действия карты истек');
        $this->card->setExpirationDate(Carbon::now()->addMonths(-5));
    }

    public function testExceptionCVV2()
    {
        $this->expectException(MonetaBadRequestException::class);
        $this->expectExceptionMessage('Некорректный формат поля "CARDCVV2"');
        $this->card->setCVV2(021);
    }
}
