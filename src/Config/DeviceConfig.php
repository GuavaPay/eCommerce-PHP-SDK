<?php

declare(strict_types=1);

namespace GuavaPay\Config;

class DeviceConfig
{
    /**
     * @param bool $browserJavaScriptEnabled
     * @param string $browserLanguage
     * @param int $browserScreenHeight
     * @param int $browserScreenWidth
     * @param int $browserTimeZone
     * @param bool $browserJavaEnabled
     * @param int $browserScreenColorDepth
     */
    public function __construct(private bool $browserJavaScriptEnabled, private string $browserLanguage, private int $browserScreenHeight, private int $browserScreenWidth, private int $browserTimeZone, private bool $browserJavaEnabled, private  int $browserScreenColorDepth)
    {

    }

    /**
     * @return bool
     */
    public function isBrowserJavaScriptEnabled(): bool
    {
        return $this->browserJavaScriptEnabled;
    }

    /**
     * @return string
     */
    public function getBrowserLanguage(): string
    {
        return $this->browserLanguage;
    }

    /**
     * @return int
     */
    public function getBrowserScreenHeight(): int
    {
        return $this->browserScreenHeight;
    }

    /**
     * @return int
     */
    public function getBrowserScreenWidth(): int
    {
        return $this->browserScreenWidth;
    }

    /**
     * @return int
     */
    public function getBrowserTimeZone(): int
    {
        return $this->browserTimeZone;
    }

    /**
     * @return bool
     */
    public function isBrowserJavaEnabled(): bool
    {
        return $this->browserJavaEnabled;
    }

    /**
     * @return int
     */
    public function getBrowserScreenColorDepth(): int
    {
        return $this->browserScreenColorDepth;
    }

}