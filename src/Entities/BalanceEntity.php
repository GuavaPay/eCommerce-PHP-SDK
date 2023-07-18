<?php

declare(strict_types=1);

namespace GuavaPay\Entities;

class BalanceEntity
{
    /**
     * @param float $amount
     */
    public function __construct(private float $amount)
    {
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

}