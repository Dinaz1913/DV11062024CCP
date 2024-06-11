<?php

require 'vendor/autoload.php';

use GuzzleHttp\Client;
use Reelz222z\CryptoExchange\User;
use Reelz222z\CryptoExchange\CryptocurrencyData;
use Reelz222z\CryptoExchange\TransactionHistory;

$users = User::loadUsers('users.json');

$username = readline("Enter your username: ");
$user = null;
foreach ($users as $u) {
    if ($u->getName() === $username) {
        $user = $u;
        break;
    }
}

if (!$user) {
    echo "User not found.\n";
    exit;
}

echo "User found: " . $user->getName()
    . " with wallet balance: "
    . $user->getWallet()->getBalance()
    . " USD\n";

$client = new Client([
    'base_uri' => 'https://sandbox-api.coinmarketcap.com',
    'headers' => [
        'X-CMC_PRO_API_KEY' => ' b54bcf4d-1bca-4e8e-9a24-22ff2c3d462c',
        'Accept' => 'application/json',
    ],
]);

function fetchTopCryptocurrencies(Client $client): array
{
    $response = $client->get('/v1/cryptocurrency/listings/latest', [
        'query' => [
            'start' => 1,
            'limit' => 10,
            'convert' => 'USD',
        ],
    ]);

    $data = json_decode($response->getBody(), true);
    return $data['data'];
}

$transactionHistory = new TransactionHistory('transactions.json');

function mainMenu(): int
{
    echo "Choose an option:\n";
    echo "1. List top cryptocurrencies\n";
    echo "2. Search cryptocurrency by symbol\n";
    echo "3. Buy cryptocurrency\n";
    echo "4. Sell cryptocurrency\n";
    echo "5. Display wallet state\n";
    echo "6. Display transaction history\n";
    $choice = readline("Enter your choice: ");
    return (int)$choice;
}

function handleUserInput
(
    User $user,
    Client $client,
    TransactionHistory $transactionHistory
): void
{
    $choice = mainMenu();
    switch ($choice) {
        case 1:
            echo "Top cryptocurrencies:\n";
            $cryptoData = new CryptocurrencyData(fetchTopCryptocurrencies($client));
            foreach ($cryptoData->getCryptocurrencies() as $crypto) {
                echo "Name: " . $crypto->getName() . " - Symbol: " . $crypto->getSymbol() . "\n";
            }
            break;
        case 2:
            $symbol = readline("Enter the symbol of the cryptocurrency you want to search: ");
            $cryptoData = new CryptocurrencyData(fetchTopCryptocurrencies($client));
            $crypto = $cryptoData->getCryptocurrencyBySymbol($symbol);
            if ($crypto) {
                echo "Name: " . $crypto->getName() . "\n";
                echo "Symbol: " . $crypto->getSymbol() . "\n";
                echo "Price: $" . $crypto->getQuote()->getPrice() . "\n";
                echo "Market Cap: $" . $crypto->getQuote()->getMarketCap() . "\n";
                echo "24h Volume: $" . $crypto->getQuote()->getVolume24h() . "\n";
            } else {
                echo "Cryptocurrency not found.\n";
            }
            break;
        case 3:
            $cryptoName = readline("Enter the name of the cryptocurrency you want to buy: ");
            $cryptoData = new CryptocurrencyData(fetchTopCryptocurrencies($client));
            $crypto = $cryptoData->getCryptocurrencyByName($cryptoName);
            if ($crypto) {
                $amount = readline("Enter the amount you want to buy: ");
                try {
                    $user->buyCryptocurrency($crypto, (float)$amount);
                    $transactionHistory->addTransaction([
                        'username' => $user->getName(),
                        'type' => 'buy',
                        'crypto' => $crypto->getName(),
                        'amount' => (float)$amount,
                        'price' => $crypto->getQuote()->getPrice(),
                        'total' => (float)$amount * $crypto->getQuote()->getPrice(),
                        'date' => date('Y-m-d H:i:s')
                    ]);
                    echo "Purchased $amount of " . $crypto->getName() . "\n";
                } catch (Exception $e) {
                    echo $e->getMessage() . "\n";
                }
            } else {
                echo "Cryptocurrency not found.\n";
            }
            break;
        case 4:
            $cryptoName = readline("Enter the name of the cryptocurrency you want to sell: ");
            $cryptoData = new CryptocurrencyData(fetchTopCryptocurrencies($client));
            $crypto = $cryptoData->getCryptocurrencyByName($cryptoName);
            if ($crypto) {
                $amount = readline("Enter the amount you want to sell: ");
                try {
                    $user->sellCryptocurrency($crypto, (float)$amount);
                    $transactionHistory->addTransaction([
                        'username' => $user->getName(),
                        'type' => 'sell',
                        'crypto' => $crypto->getName(),
                        'amount' => (float)$amount,
                        'price' => $crypto->getQuote()->getPrice(),
                        'total' => (float)$amount * $crypto->getQuote()->getPrice(),
                        'date' => date('Y-m-d H:i:s')
                    ]);
                    echo "Sold $amount of " . $crypto->getName() . "\n";
                } catch (Exception $e) {
                    echo $e->getMessage() . "\n";
                }
            } else {
                echo "Cryptocurrency not found.\n";
            }
            break;
        case 5:
            echo "Wallet state:\n";
            echo "Balance: " . $user->getWallet()->getBalance() . " USD\n";
            echo "Portfolio:\n";
            foreach ($user->getPortfolio() as $crypto => $amount) {
                echo "$crypto: $amount\n";
            }
            break;
        case 6:
            echo "Transaction History:\n";
            foreach ($transactionHistory->getTransactions() as $transaction) {
                echo $transaction['date'] . ": "
                    . $transaction['type'] . " "
                    . $transaction['amount']
                    . " of " . $transaction['crypto']
                    . " at $" . $transaction['price']
                    . " each. Total: $" . $transaction['total'] . "\n";
            }
            break;
        default:
            echo "Invalid choice.\n";
            break;
    }
}

while (true) {
    handleUserInput($user, $client, $transactionHistory);
    $continue = readline("Do you want to perform another action? (yes/no): ");
    if (strtolower($continue) !== 'yes') {
        break;
    }
}

User::saveUsers('users.json', $users);
$transactionHistory->saveTransactions('transactions.json');
