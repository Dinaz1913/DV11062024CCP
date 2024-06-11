<?php

namespace Reelz222z\CryptoExchange;

class Quote
{
    private float $price;
    private float $volume24h;
    private float $volumeChange24h;
    private float $percentChange1h;
    private float $percentChange24h;
    private float $percentChange7d;
    private float $marketCap;
    private float $marketCapDominance;
    private float $fullyDilutedMarketCap;
    private string $lastUpdated;

    public function __construct(
        float $price,
        float $volume24h,
        float $volumeChange24h,
        float $percentChange1h,
        float $percentChange24h,
        float $percentChange7d,
        float $marketCap,
        float $marketCapDominance,
        float $fullyDilutedMarketCap,
        string $lastUpdated
    ) {
        $this->price = $price;
        $this->volume24h = $volume24h;
        $this->volumeChange24h = $volumeChange24h;
        $this->percentChange1h = $percentChange1h;
        $this->percentChange24h = $percentChange24h;
        $this->percentChange7d = $percentChange7d;
        $this->marketCap = $marketCap;
        $this->marketCapDominance = $marketCapDominance;
        $this->fullyDilutedMarketCap = $fullyDilutedMarketCap;
        $this->lastUpdated = $lastUpdated;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getVolume24h(): float
    {
        return $this->volume24h;
    }

    public function getVolumeChange24h(): float
    {
        return $this->volumeChange24h;
    }

    public function getPercentChange1h(): float
    {
        return $this->percentChange1h;
    }

    public function getPercentChange24h(): float
    {
        return $this->percentChange24h;
    }

    public function getPercentChange7d(): float
    {
        return $this->percentChange7d;
    }

    public function getMarketCap(): float
    {
        return $this->marketCap;
    }

    public function getMarketCapDominance(): float
    {
        return $this->marketCapDominance;
    }

    public function getFullyDilutedMarketCap(): float
    {
        return $this->fullyDilutedMarketCap;
    }

    public function getLastUpdated(): string
    {
        return $this->lastUpdated;
    }
}
