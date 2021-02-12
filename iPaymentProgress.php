<?php

namespace veselin\Settle;

interface iPaymentProgress
{
    public function success(string $paymentId);
    public function fail(string $paymentId);
}