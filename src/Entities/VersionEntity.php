<?php

declare(strict_types=1);

namespace GuavaPay\Entities;

class VersionEntity
{

    /**
     * @param int $version
     */
    public function __construct(private int $version)
    {
    }

    /**
     * @return int
     */
    public function getVersion(): int
    {
        return $this->version;
    }

}