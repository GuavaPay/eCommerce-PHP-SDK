<?php

declare(strict_types=1);

namespace GuavaPay\Entities;

class OrderInfoEntity
{
    /**
     * @param string|null $orderId
     * @param string $description
     * @param float|null $amount
     * @param int|null $currency
     * @param float|null $fee
     * @param string|null $timestamp
     * @param string $status
     * @param string $statusId
     * @param string|null $provider
     * @param string|null $card
     * @param string|null $rrn
     * @param bool $isSuccess
     * @param string|null $auth
     */
    public function __construct(private string | null $orderId, private string $description, private float | null $amount, private int | null $currency, private float | null $fee, private string | null $timestamp, private string $status, private string $statusId, private string | null $provider, private string | null $card, private string | null $rrn, private bool $isSuccess, private string | null $auth)
    {
    }


    /**
     * @return string
     */
    public function getOrderId(): string | null
    {
        return $this->orderId;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return float
     */
    public function getAmount(): float | null
    {
        return $this->amount;
    }

    /**
     * @return float
     */
    public function getCurrency(): float | null
    {
        return $this->currency;
    }

    /**
     * @return float
     */
    public function getFee(): float
    {
        return $this->fee;
    }

    /**
     * @return string
     */
    public function getTimestamp(): string | null
    {
        return $this->timestamp;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getStatusId(): string
    {
        return $this->statusId;
    }

    /**
     * @return string
     */
    public function getProvider(): string
    {
        return $this->provider;
    }

    /**
     * @return string
     */
    public function getCard(): string | null
    {
        return $this->card;
    }

    /**
     * @return string
     */
    public function getRrn(): string
    {
        return $this->rrn;
    }

    /**
     * @return bool
     */
    public function getIsSuccess(): bool
    {
        return $this->isSuccess;
    }

    /**
     * @return string
     */
    public function getAuth(): string
    {
        return $this->auth;
    }
}
