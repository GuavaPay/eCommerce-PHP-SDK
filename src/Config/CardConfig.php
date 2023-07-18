<?php

declare(strict_types=1);

namespace GuavaPay\Config;

use \DateTime;

class CardConfig
{
    public function __construct(private string $pan, private DateTime $expiry, private string $cvc, private string $cardHolder)
    {
    }

    /**
     * @return string
     */
    public function getPan(): string
    {
        return $this->pan;
    }

    /**
     * @return string
     */
    public function getCvc(): string
    {
        return $this->cvc;
    }

    /**
     * @return string
     */
    public function getCardHolder(): string
    {
        return $this->cardHolder;
    }

    /**
     * @return string
     */
    public function getExpiryMonth() : string
    {
        return $this->expiry->format('m');
    }

    /**
     * @return string
     */
    public function getExpiryYear() : string
    {
        return $this->expiry->format('Y');
    }
}