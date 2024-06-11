<?php

namespace Reelz222z\CryptoExchange;

class Wallet
{
    protected float $balance;

    public function __construct(float $initialBalance)
    {
        $this->balance = $initialBalance;
    }

    public function getBalance(): float
    {
        return $this->balance;
    }

    public function buyCryptocurrency(Cryptocurrency $crypto, float $amount): void
    {
        $totalCost = $amount * $crypto->getQuote()->getPrice();
        if ($this->balance < $totalCost) {
            throw new \Exception("Insufficient funds.");
        }

        $this->balance -= $totalCost;
    }

    public function sellCryptocurrency(Cryptocurrency $crypto, float $amount): void
    {
        $totalValue = $amount * $crypto->getQuote()->getPrice();
        $this->balance += $totalValue;
    }
}
