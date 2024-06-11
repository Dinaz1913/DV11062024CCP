<?php

namespace Reelz222z\CryptoExchange;

class Cryptocurrency extends Entity
{
    protected string $id;
    protected string $name;
    protected string $symbol;
    protected string $slug;
    protected int $cmcRank;
    protected int $numMarketPairs;
    protected float $circulatingSupply;
    protected float $totalSupply;
    protected float $maxSupply;
    protected string $lastUpdated;
    protected string $dateAdded;
    protected Quote $quote;

    public function __construct(
        string $id,
        string $name,
        string $symbol,
        string $slug,
        int $cmcRank,
        int $numMarketPairs,
        float $circulatingSupply,
        float $totalSupply,
        float $maxSupply,
        string $lastUpdated,
        string $dateAdded,
        Quote $quote
    ) {
        parent::__construct();
        $this->id = $id;
        $this->name = $name;
        $this->symbol = $symbol;
        $this->slug = $slug;
        $this->cmcRank = $cmcRank;
        $this->numMarketPairs = $numMarketPairs;
        $this->circulatingSupply = $circulatingSupply;
        $this->totalSupply = $totalSupply;
        $this->maxSupply = $maxSupply;
        $this->lastUpdated = $lastUpdated;
        $this->dateAdded = $dateAdded;
        $this->quote = $quote;
    }

    // Getters
    public function getId(): string { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getSymbol(): string { return $this->symbol; }
    public function getSlug(): string { return $this->slug; }
    public function getCmcRank(): int { return $this->cmcRank; }
    public function getNumMarketPairs(): int { return $this->numMarketPairs; }
    public function getCirculatingSupply(): float { return $this->circulatingSupply; }
    public function getTotalSupply(): float { return $this->totalSupply; }
    public function getMaxSupply(): float { return $this->maxSupply; }
    public function getLastUpdated(): string { return $this->lastUpdated; }
    public function getDateAdded(): string { return $this->dateAdded; }
    public function getQuote(): Quote { return $this->quote; }
}
