<?php

namespace veselin\Settle;


class PaymentStatus implements iPaymentProgress
{
    public function success(string $paymentId)
    {
        // TODO: Implement success() method.
    }

    public function fail(string $paymentId)
    {
        // TODO: Implement fail() method.
    }
}