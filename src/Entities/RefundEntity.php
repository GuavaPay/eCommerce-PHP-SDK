<?php

declare(strict_types=1);

namespace GuavaPay\Entities;

class RefundEntity
{
    /**
     * @param string $code
     */
    public function __construct(private string $code)
    {
    }

    /**
     * @return bool
     */
    public function getRefundStatus() : bool
    {
        return ($this->code === "0");
    }
}