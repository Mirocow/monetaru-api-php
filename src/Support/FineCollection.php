<?php

namespace AvtoDev\MonetaApi\Support;

use AvtoDev\MonetaApi\Types\Fine;

/**
 * Class FineCollection.
 *
 * Коллекция штрафов
 */
class FineCollection extends AbstractCollection
{
    /**
     * @var Fine[]
     */
    protected $stack = [];

    public function push(Fine $fine)
    {
        $this->stack[] = $fine;
    }

    /**
     * Полная сумма штрафов.
     *
     * @return int
     */
    public function totalAmount()
    {
        $totalAmount = 0;
        /** @var Fine $fine */
        foreach ($this->stack as $fine) {
            $totalAmount += $fine->getTotalAmount();
        }

        return $totalAmount;
    }

    /**
     * Сумма для оплаты.
     *
     * @return int
     */
    public function needToPayAmount()
    {
        $amount = 0;

        foreach ($this->stack as $fine) {
            if (! $fine->getIsPaid()) {
                $amount += $fine->getAmount();
            }
        }

        return $amount;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        $return = ['Fines' => []];
        foreach ($this->stack as $fine) {
            $return['Fines'][] = $fine->toArray();
        }

        return $return;
    }

    /**
     * {@inheritdoc}
     *
     * @return Fine
     */
    public function current()
    {
        return parent::current();
    }
}
