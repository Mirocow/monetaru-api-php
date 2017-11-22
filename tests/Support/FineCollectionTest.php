<?php

namespace AvtoDev\MonetaApi\Tests\Support;

use PHPUnit\Framework\TestCase;
use AvtoDev\MonetaApi\Types\Fine;
use AvtoDev\MonetaApi\Support\FineCollection;
use AvtoDev\MonetaApi\References\FineReference;

class FineCollectionTest extends TestCase
{
    /**
     * @var FineCollection
     */
    protected $collection;

    protected $totalAmount     = 0;

    protected $needToPayAmount = 0;

    protected function setUp()
    {
        parent::setUp();
        $this->collection = new FineCollection;
        $this->generateFines();
    }

    public function testTotalAmount()
    {
        $this->assertEquals($this->totalAmount, $this->collection->totalAmount());
    }

    public function testNeedToPayAmount()
    {
        $this->assertEquals($this->needToPayAmount, $this->collection->needToPayAmount());
    }

    protected function generateFines()
    {
        $count = mt_rand(1, 10);
        for ($i = 0; $i < $count; $i++) {
            $fine = new Fine;

            $this->totalAmount += $totalAmount = mt_rand(100, 5000);
            $this->needToPayAmount += $amount  = mt_rand(100, $totalAmount);
            $fine->configure([
                FineReference::FIELD_AMOUNT       => $amount,
                FineReference::FIELD_TOTAL_AMOUNT => $totalAmount,
            ]);
            $this->collection->push($fine);
        }
    }
}
