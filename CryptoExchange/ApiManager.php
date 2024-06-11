<?php

namespace Reelz222z\CryptoExchange;

use GuzzleHttp\Client;

class ApiManager
{
    private Client $client;
    private int $apiCallCount = 0;
    private const int MAX_API_CALLS = 10;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://sandbox-api.coinmarketcap.com',
            'headers' => [
                'X-CMC_PRO_API_KEY' => 'b54bcf4d-1bca-4e8e-9a24-22ff2c3d462c',
                'Accept' => 'application/json',
            ],
        ]);
    }

    public function fetchTopCryptocurrencies(): array
    {
        if ($this->apiCallCount >= self::MAX_API_CALLS) {
            throw new \Exception("API call limit reached");
        }

        $response = $this->client->get('/v1/cryptocurrency/listings/latest', [
            'query' => [
                'start' => 1,
                'limit' => 10,
                'convert' => 'USD',
            ],
        ]);

        $this->apiCallCount++;
        $data = json_decode($response->getBody(), true);
        return $data['data'];
    }
}
