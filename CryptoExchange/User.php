<?php

namespace Reelz222z\CryptoExchange;

class User
{
    protected string $name;
    protected Wallet $wallet;
    protected array $portfolio; // Track owned cryptocurrencies

    public function __construct(string $name, float $balance)
    {
        $this->name = $name;
        $this->wallet = new Wallet($balance);
        $this->portfolio = [];
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getWallet(): Wallet
    {
        return $this->wallet;
    }

    public function getPortfolio(): array
    {
        return $this->portfolio;
    }

    public function buyCryptocurrency(Cryptocurrency $crypto, float $amount): void
    {
        $this->wallet->deduct($crypto->getQuote()->getPrice() * $amount);
        $symbol = $crypto->getSymbol();
        if (!isset($this->portfolio[$symbol])) {
            $this->portfolio[$symbol] = 0;
        }
        $this->portfolio[$symbol] += $amount;
    }

    public function sellCryptocurrency(Cryptocurrency $crypto, float $amount): void
    {
        $symbol = $crypto->getSymbol();
        if (!isset($this->portfolio[$symbol]) || $this->portfolio[$symbol] < $amount) {
            throw new \Exception("Not enough cryptocurrency to sell.");
        }
        $this->portfolio[$symbol] -= $amount;
        $this->wallet->add($crypto->getQuote()->getPrice() * $amount);
    }

    public static function loadUsers(string $filePath): array
    {
        $json = file_get_contents($filePath);
        $data = json_decode($json, true);

        $users = [];
        foreach ($data as $item) {
            $user = new self($item['name'], $item['wallet']);
            $user->portfolio = $item['portfolio'] ?? [];
            $users[] = $user;
        }

        return $users;
    }

    public static function saveUsers(string $filePath, array $users): void
    {
        $data = [];
        foreach ($users as $user) {
            $data[] = [
                'name' => $user->getName(),
                'wallet' => $user->getWallet()->getBalance(),
                'portfolio' => $user->getPortfolio()
            ];
        }

        file_put_contents($filePath, json_encode($data, JSON_PRETTY_PRINT));
    }
}
