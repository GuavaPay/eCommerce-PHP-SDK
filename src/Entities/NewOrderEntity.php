<?php

declare(strict_types=1);

namespace GuavaPay\Entities;

class NewOrderEntity
{
    /**
     * @param string $orderId
     * @param string $formUrl
     */
    public function __construct(private string $orderId, private string $formUrl)
    {
    }

    /**
     * @return string
     */
    public function getOrderId(): string
    {
        return $this->orderId;
    }

    /**
     * @return string
     */
    public function getFormUrl(): string
    {
        return $this->formUrl;
    }

}
