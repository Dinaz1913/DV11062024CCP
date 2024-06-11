<?php

namespace Reelz222z\CryptoExchange;

class CryptocurrencyData
{
    private array $cryptocurrencies;

    public function __construct(array $cryptocurrencies)
    {
        $this->cryptocurrencies = [];
        foreach ($cryptocurrencies as $crypto) {
            $this->cryptocurrencies[] = new Cryptocurrency(
                $crypto['id'],
                $crypto['name'],
                $crypto['symbol'],
                $crypto['slug'],
                $crypto['cmc_rank'],
                $crypto['num_market_pairs'],
                $crypto['circulating_supply'],
                $crypto['total_supply'],
                $crypto['max_supply'],
                $crypto['last_updated'],
                $crypto['date_added'],
                new Quote(
                    $crypto['quote']['USD']['price'],
                    $crypto['quote']['USD']['volume_24h'],
                    $crypto['quote']['USD']['volume_change_24h'],
                    $crypto['quote']['USD']['percent_change_1h'],
                    $crypto['quote']['USD']['percent_change_24h'],
                    $crypto['quote']['USD']['percent_change_7d'],
                    $crypto['quote']['USD']['market_cap'],
                    $crypto['quote']['USD']['market_cap_dominance'],
                    $crypto['quote']['USD']['fully_diluted_market_cap'],
                    $crypto['quote']['USD']['last_updated']
                )
            );
        }
    }

    public function getCryptocurrencies(): array
    {
        return $this->cryptocurrencies;
    }

    public function getCryptocurrencyBySymbol(string $symbol): ?Cryptocurrency
    {
        foreach ($this->cryptocurrencies as $crypto) {
            if ($crypto->getSymbol() === $symbol) {
                return $crypto;
            }
        }
        return null;
    }

    public function getCryptocurrencyByName(string $name): ?Cryptocurrency
    {
        foreach ($this->cryptocurrencies as $crypto) {
            if ($crypto->getName() === $name) {
                return $crypto;
            }
        }
        return null;
    }
}
