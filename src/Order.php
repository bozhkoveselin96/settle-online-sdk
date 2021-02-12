<?php

namespace veselin\Settle;

class Order
{
    private $amount;
    private $description;
    private $phone;
    private $hookUrl;

    public function __construct($amount, $description, $phone, $hookUrl)
    {
        $this->amount       = $amount;
        $this->description  = $description;
        $this->phone        = $phone;
        $this->hookUrl      = $hookUrl;
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

    public function getHookUrl()
    {
        return $this->hookUrl;
    }
}