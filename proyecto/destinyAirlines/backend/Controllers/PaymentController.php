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
        //pasarla a pdf y enviarla al email

        $invoiceReturnData;
        if(isset($dedodedPaymentToken['response']->data->idInvoiceR)) {
            $invoiceModel->updateIsPaid($dedodedPaymentToken['response']->data->idInvoiceR);
            $invoiceReturnData = $invoiceTool->generateInvoiceData($dedodedPaymentToken['response']->data->idUser, $dedodedPaymentToken['response']->data->idInvoiceR);
            //pasarla a pdf y enviarla al email

        }
        //si el token ha caducado se envía la info por GET para recogerla allí por js
        header('Location: ./Views/successPaymentPage.html');
        exit;
    }

    public function paypalRedirectCancel()
    {
        header('Location: ./Views/cancelPaymentPage.html');
        exit;
    }
}
