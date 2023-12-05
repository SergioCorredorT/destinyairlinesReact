<?php
final class PaymentTool
{
    /*
$clientId: Este es el ID del cliente que obtienes de tu cuenta de PayPal. Es un identificador único para tu aplicación que PayPal utiliza para autenticar las solicitudes de API.

$clientSecret: Este es el secreto del cliente que obtienes de tu cuenta de PayPal. Se utiliza junto con el ID del cliente para autenticar las solicitudes de API.

$total: Este es el total a pagar. Deberías calcular este valor en tu aplicación basándote en los detalles del vuelo que el cliente ha seleccionado.

$currency: Esta es la moneda en la que se realizará el pago. Deberías establecer este valor en función de la moneda que aceptas en tu sitio web (por ejemplo, ‘USD’ para dólares estadounidenses, ‘EUR’ para euros, etc.).

$returnUrl: Esta es la URL a la que PayPal redirigirá al cliente después de que haya completado el pago. Deberías establecer este valor en la URL de tu sitio web donde manejas las transacciones exitosas.

$cancelUrl: Esta es la URL a la que PayPal redirigirá al cliente si decide cancelar el pago. Deberías establecer este valor en la URL de tu sitio web donde manejas las cancelaciones de pago.
    
    */
    /*
    public function createPaymentPaypalOLD($clientId, $clientSecret, $total, $currency, $returnUrl, $cancelUrl)
    {
        //composer require paypal/rest-api-sdk-php
        require 'vendor/autoload.php';

        define('PAYMENT_METHOD', 'paypal');
        define('PAYMENT_INTENT', 'sale');

        $apiContext = new \PayPal\Rest\ApiContext(
            new \PayPal\Auth\OAuthTokenCredential(
                $clientId,     // ClientID
                $clientSecret  // ClientSecret
            )
        );

        $apiContext->setConfig(
            array(
                'mode' => 'sandbox',
            )
        );

        $payer = new \PayPal\Api\Payer();
        $payer->setPaymentMethod(PAYMENT_METHOD);

        $amount = new \PayPal\Api\Amount();
        $amount->setTotal($total);
        $amount->setCurrency($currency);

        $transaction = new \PayPal\Api\Transaction();
        $transaction->setAmount($amount);

        $redirectUrls = new \PayPal\Api\RedirectUrls();
        $redirectUrls->setReturnUrl($returnUrl)
            ->setCancelUrl($cancelUrl);

        $payment = new \PayPal\Api\Payment();
        $payment->setIntent(PAYMENT_INTENT)
            ->setPayer($payer)
            ->setTransactions(array($transaction))
            ->setRedirectUrls($redirectUrls);

        try {
            $payment->create($apiContext);
            return $payment;
        } catch (Exception $ex) {
            error_log($ex->getMessage());
            return false;
        }
    }
*/
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
