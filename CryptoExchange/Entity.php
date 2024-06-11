<?php

namespace Reelz222z\CryptoExchange;

class Entity
{
    protected string $infiniteSupply;

    public function __construct(string $infiniteSupply = "")
    {
        $this->infiniteSupply = $infiniteSupply;
    }
}
