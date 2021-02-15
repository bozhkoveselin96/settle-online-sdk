<?php

namespace veselin\Settle;

require_once('config.php');

class PaymentGateway
{
    private $merchantId;
    private $userId;
    private $secret;
    private $token;
    private $payment;

    public function __construct(iPaymentProgress $payment)
    {
        $this->payment      = $payment;
        $this->merchantId   = MERCHANT_ID;
        $this->userId       = USER_ID;
        $this->secret       = SECRET;
    }

    /**
     * @param float $amount
     * @param string $description
     * @param string | null $phone
     * @return false|object
     * @throws SettleAppException
     */
    public function pay(float $amount, string $description, $phone = null)
    {
        if ($this->getToken() === null) {
            $this->setToken($this->getTokenFromSettApp());
        }
        $order = new Order($amount, $description, $phone);
        $payment = $this->createOrder($order);
        if (!$payment) {
            return false;
        }

        return $payment;
    }


    /**
     * @return string | void
     * @throws SettleAppException
     */
    private function getTokenFromSettApp() : string
    {
        $path = 'authorize';
        $headers = ['Content-Type: application/json'];
        $configParameters = [
            'merchantId' => $this->merchantId,
            'userId' => $this->userId,
            'secret' => $this->secret
        ];

        $response = $this->requestToSettleApp($path, $headers, $configParameters);
        if ($response) {
            return $response->token;
        }
    }

    /**
     * @param Order $order
     * @return object
     * @throws SettleAppException
     */
    private function createOrder(Order $order)
    {
        $path = 'order';
        $headers = [
            'Content-Type: application/json',
            "x-settapp-token: " . $this->getToken()
        ];
        $orderParameters = [
            'amount'        => $order->getAmount(),
            'description'   => $order->getDescription(),
            'phone'         => $order->getPhone()
        ];

        return $this->requestToSettleApp($path, $headers, $orderParameters);
    }


    /**
     * @param string $paymentId
     * @param string $token
     * @throws SettleAppException
     */
    public function checkPaymentStatus(string $paymentId, string $token)
    {
        //TODO: make a route to call the method.
        $path = "status/$paymentId";
        $headers = [
            'Content-Type: application/json',
            "x-settapp-token: $token"
        ];

        $response = $this->requestToSettleApp($path, $headers);
        switch ($response->status) {
            case STATUS_SUCCESS:
                return $this->payment->success($paymentId);
            case STATUS_FAIL:
                return $this->payment->fail($paymentId);
            case STATUS_PENDING:
                return STATUS_PENDING;
        }
    }


    /**
     * @param string $path
     * @param array $headers
     * @param bool | array $postFields
     * @return object | void
     * @throws SettleAppException
     */
    private function requestToSettleApp(string $path, array $headers, $postFields = false)
    {
        $url = BASE_URL . $path;
        $curlOptions = [
            CURLOPT_URL             => $url,
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_HTTPHEADER      => $headers
        ];

        if ($postFields !== false) {
            $curlOptions[CURLOPT_POST]          = true;
            $curlOptions[CURLOPT_POSTFIELDS]    = json_encode($postFields);
        }

        $ch = curl_init();
        curl_setopt_array($ch, $curlOptions);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $responseBody   = json_decode(curl_exec($ch));
        $httpCode       = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);
        switch ($httpCode) {
            case 200:
                return (object)$responseBody;
            case 400:
                if ($responseBody !== null) {
                    if (isset($responseBody->error)) {
                        throw new SettleAppException($responseBody->error, 400);
                    }
                    if ($responseBody->error_description === "Token 013344fb-a60e-4cff-ba74-19d7c52899c7 has expired.") {
                        $token = $this->getTokenFromSettApp();
                        if ($token) {
                            $this->setToken($token);
                            $headers = [
                                'Content-Type: application/json',
                                "x-settapp-token: $token"
                            ];
                            return $this->requestToSettleApp($path, $headers, $postFields);
                        }
                    }
                    throw new SettleAppException($responseBody->error_description, 400);
                }
                throw new SettleAppException('Bad request', 400);
            case 500:
            case 502:
                throw new SettleAppException('Something went wrong.', 500);
        }
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param string $token
     */
    private function setToken(string $token)
    {
        $this->token = $token;
    }
}