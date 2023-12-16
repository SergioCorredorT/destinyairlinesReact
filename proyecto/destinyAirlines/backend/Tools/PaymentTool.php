<?php
final class PaymentTool
{
    public function createPaymentPaypal($clientId, $clientSecret, $total, $currency, $returnUrl, $cancelUrl)
    {
        $orderData = [
            "intent" => "CAPTURE",
            "purchase_units" => [
                [
                    "amount" => [
                        "currency_code" => $currency,
                        "value" => $total
                    ]
                ]
            ],
            "application_context" => [
                "return_url" => $returnUrl,
                "cancel_url" => $cancelUrl
            ]
        ];

        try {
            $response = $this->sendRequest('POST', '/v2/checkout/orders', $orderData, $clientId, $clientSecret);
            return $response;
        } catch (Exception $ex) {
            error_log($ex->getMessage());
            return false;
        }
    }

    private function sendRequest($method, $url, $data, $clientId, $clientSecret)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://api.paypal.com' . $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: Bearer ' . $this->getAccessToken($clientId, $clientSecret);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        if (!empty($data)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            throw new Exception(curl_error($ch));
        }
        curl_close($ch);

        return json_decode($result);
    }

    private function getAccessToken($clientId, $clientSecret)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://api.paypal.com/v1/oauth2/token');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_USERPWD, $clientId . ':' . $clientSecret);
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=client_credentials');

        $headers = array();
        $headers[] = 'Accept: application/json';
        $headers[] = 'Accept-Language: en_US';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            throw new Exception(curl_error($ch));
        }
        curl_close($ch);

        $response = json_decode($result);
        return $response->access_token;
    }
}
