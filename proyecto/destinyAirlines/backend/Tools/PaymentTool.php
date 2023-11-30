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
    public function createPaymentPaypal($clientId, $clientSecret, $total, $currency, $returnUrl, $cancelUrl)
    {
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
}
