<?php

namespace veselin\Settle;

class Order
{
    private $amount;
    private $description;
    private $phone;
    private $hookUrl;

    public function __construct(float $amount, string $description, string $hookUrl, $phone)
    {
        $this->amount       = $amount;
        $this->description  = $description;
        $this->phone        = $phone;
        $this->hookUrl      = $hookUrl;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getPhone()
    {
        return $this->phone;
    }

    public function getHookUrl(): string
    {
        return $this->hookUrl;
    }
}