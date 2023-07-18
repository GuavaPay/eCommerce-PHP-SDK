<?php

declare(strict_types=1);

namespace GuavaPay\Entities;

class PaymentEntity
{
    public function __construct(private string $info, private string $acsUrl, private string $cReq)
    {
    }

    /**
     * @return string
     */
    public function getInfo(): string
    {
        return $this->info;
    }

    /**
     * @return string
     */
    public function getAcsUrl(): string
    {
        return $this->acsUrl;
    }

    /**
     * @return string
     */
    public function getCReq(): string
    {
        return $this->cReq;
    }
}