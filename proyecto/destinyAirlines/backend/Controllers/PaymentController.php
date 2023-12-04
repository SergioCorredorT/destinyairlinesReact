<?php
//Ver vuelos/reservas hechos y pendientes, crear reserva, editar los servicios contratados, crear nuevo vuelo

require_once './Controllers/BaseController.php';
require_once './Tools/SessionTool.php';
final class PaymentController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function debugPaypalRedirectOk()
    {
        require_once './Tools/TokenTool.php';
        require_once './Tools/IniTool.php';
        $iniTool = new IniTool('./Config/cfg.ini');
        $tokenSettings = $iniTool->getKeysAndValues('tokenSettings');

        //CREAR TOKEN de 3 horas (caducidad de paypal en su web)
        $data = ['id' => 138, 'idUser' => 138, 'idInvoiceD' => 26, 'type' => 'paypalredirectok'];
        $paymentToken = TokenTool::generateToken($data, intval($tokenSettings['secondsTimeLifePaymentReturnUrl']));
        $this->paypalRedirectOk(['token' => $paymentToken]);
    }

    public function paypalRedirectOk(array $GET)
    {
        require_once './Sanitizers/TokenSanitizer.php';
        require_once './Validators/TokenValidator.php';
        require_once './Tools/TokenTool.php';
        require_once './Tools/InvoiceTool.php';

        $paymentDetails = [
            'token'           => $GET['token'] ?? ''
        ];

        $paymentDetails['token'] = TokenSanitizer::sanitizeToken($paymentDetails['token']);
        if (!TokenValidator::validateToken($paymentDetails['token'])) {
            return false;
        }

        $dedodedPaymentToken = TokenTool::decodeAndCheckToken($paymentDetails['token'], 'paypalredirectok');
        if (isset($dedodedPaymentToken['errorName'])) {
            error_log($dedodedPaymentToken['errorName']);
            header('Location: ./Views/cancelPaymentPage.html');
            exit;
        }

        //Cambiar el isPais de la tabla invoices aquí
        $invoiceModel = new InvoiceModel();
        $invoiceModel->updateIsPaid($dedodedPaymentToken['response']->data->idInvoiceD);

        SessionTool::remove('departure');
        SessionTool::remove('return');

        $invoiceTool = new InvoiceTool();
        //Generamos las facturas con los id que contiene el token válido
        $invoiceDepartureData = $invoiceTool->generateInvoiceData($dedodedPaymentToken['response']->data->idUser, $dedodedPaymentToken['response']->data->idInvoiceD);
error_log(print_r($invoiceDepartureData,true));
        $invoiceDepartureHtml = $invoiceTool->generateInvoiceHtml($invoiceDepartureData);
error_log($invoiceDepartureHtml);
        //pasarla a pdf y enviarla al email

        $invoiceReturnData;
        if (isset($dedodedPaymentToken['response']->data->idInvoiceR)) {
            $invoiceModel->updateIsPaid($dedodedPaymentToken['response']->data->idInvoiceR);
            $invoiceReturnData = $invoiceTool->generateInvoiceData($dedodedPaymentToken['response']->data->idUser, $dedodedPaymentToken['response']->data->idInvoiceR);
            $invoiceReturnHtml = $invoiceTool->generateInvoiceHtml($invoiceReturnData);
            //pasarla a pdf y enviarla al email

        }
        //si el token ha caducado se envía la info por GET para recogerla allí por js
        //header('Location: ./Views/successPaymentPage.html');
        //exit;
    }

    public function paypalRedirectCancel()
    {
        header('Location: ./Views/cancelPaymentPage.html');
        exit;
    }
}
