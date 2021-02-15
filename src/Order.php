<?php

namespace veselin\Settle;

class Order
{
    private $amount;
    private $description;
    private $phone;

    public function __construct($amount, $description, $phone)
    {
        $this->amount       = $amount;
        $this->description  = $description;
        $this->phone        = $phone;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getPhone()
    {
        return $this->phone;
    }
}