<?php
//Ver vuelos/reservas hechos y pendientes, crear reserva, editar los servicios contratados, crear nuevo vuelo

require_once ROOT_PATH . '/Controllers/BaseController.php';
final class PaymentController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function paypalRedirectOk(array $GET): bool
    {
        $paymentDetails = [
            'token'           => $GET['token'] ?? ''
        ];

        $tokenSanitized = $this->processData->processData(['accessToken'=>$paymentDetails['token']], 'Token');
        if (!$tokenSanitized['accessToken']) {
            return false;
        }
        $tokenSanitized=$tokenSanitized['accessToken'];

        $paymentDetails['token']= $tokenSanitized;

        $dedodedPaymentToken = TokenTool::decodeAndCheckToken(strval($paymentDetails['token']), 'paypalredirectok');
        if (isset($dedodedPaymentToken['errorName'])) {
            error_log($dedodedPaymentToken['errorName']);
            return $this->paypalRedirectCancel();
        }

        //Cambiar el isPais de la tabla invoices aquí
        $invoiceModel = new InvoiceModel();
        $invoiceModel->updateIsPaid($dedodedPaymentToken['response']->data->idInvoiceD);

        SessionTool::remove('departure');
        SessionTool::remove('return');

        $invoiceTool = new InvoiceTool();
        $pdfTool = new PdfTool();
        $emailTool = new EmailTool();
        $cfgOriginEmailIni = $this->iniTool->getKeysAndValues("originEmail");
        $subject = 'Factura de su nuevo viaje';
        //Generamos las facturas con los id que contiene el token válido
        $invoiceDepartureData = $invoiceTool->generateInvoiceData($dedodedPaymentToken['response']->data->idUser, $dedodedPaymentToken['response']->data->idInvoiceD);
        $invoiceDepartureHtml = $invoiceTool->generateInvoiceHtml($invoiceDepartureData);
        $invoiceDeparturePdf = $pdfTool->generatePdfFromHtml($invoiceDepartureHtml);
        $emailTool->sendEmail(
            [
                'toEmail' => $invoiceDepartureData['userData']['emailAddress'],
                'fromEmail' => $cfgOriginEmailIni['email'],
                'fromPassword' => $cfgOriginEmailIni['password'],
                'subject' => $subject
            ],
            'invoiceTemplate',
            $invoiceDeparturePdf,
            'invoice'
        );

        $invoiceReturnData = null;
        if (isset($dedodedPaymentToken['response']->data->idInvoiceR)) {
            $invoiceModel->updateIsPaid($dedodedPaymentToken['response']->data->idInvoiceR);
            $invoiceReturnData = $invoiceTool->generateInvoiceData($dedodedPaymentToken['response']->data->idUser, $dedodedPaymentToken['response']->data->idInvoiceR);
            $invoiceReturnHtml = $invoiceTool->generateInvoiceHtml($invoiceReturnData);
            $invoiceReturnPdf = $pdfTool->generatePdfFromHtml($invoiceReturnHtml);
            $emailTool->sendEmail(
                [
                    'toEmail' => $invoiceReturnData['userData']['emailAddress'],
                    'fromEmail' => $cfgOriginEmailIni['email'],
                    'fromPassword' => $cfgOriginEmailIni['password'],
                    'subject' => $subject
                ],
                'invoiceTemplate',
                $invoiceReturnPdf,
                'invoice'
            );
        }
        //si el token ha caducado se envía la info por GET para recogerla allí por js
        return true;
        //header('Location: ./Views/successPaymentPage.html');
        //exit;
    }

    public function paypalRedirectCancel()
    {
        header('Location: ' . ROOT_PATH . '/Views/cancelPaymentPage.html');
        return false;
    }
}
