<?php

namespace Reelz222z\CryptoExchange;

class TransactionHistory
{
    protected string $filePath;
    protected array $transactions;

    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
        $this->transactions = $this->loadTransactions();
    }

    protected function loadTransactions(): array
    {
        if (!file_exists($this->filePath)) {
            return [];
        }

        $json = file_get_contents($this->filePath);
        return json_decode($json, true) ?? [];
    }

    public function addTransaction(array $transaction): void
    {
        // Ensure all necessary keys are present
        $transaction = array_merge([
            'date' => date('Y-m-d H:i:s'),
            'type' => '',
            'username' => '',
            'crypto' => '',
            'amount' => 0,
            'price' => 0,
            'total' => 0
        ], $transaction);

        $this->transactions[] = $transaction;
        $this->saveTransactions();
    }

    public function saveTransactions(): void
    {
        $json = json_encode($this->transactions, JSON_PRETTY_PRINT);
        file_put_contents($this->filePath, $json);
    }

    public function getTransactionsByUsername(string $username): array
    {
        return array_filter($this->transactions, function ($transaction) use ($username) {
            return $transaction['username'] === $username;
        });
    }
}
