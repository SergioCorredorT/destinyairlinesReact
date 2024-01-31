<?php
//Ver vuelos/reservas hechos y pendientes, crear reserva, editar los servicios contratados, crear nuevo vuelo

require_once ROOT_PATH . '/Controllers/BaseController.php';
final class PaymentController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function paypalRedirectOk(array $GET)
    {
        require_once ROOT_PATH . '/Sanitizers/TokenSanitizer.php';
        require_once ROOT_PATH . '/Validators/TokenValidator.php';

        $paymentDetails = [
            'token'           => $GET['token'] ?? ''
        ];
        var_dump($paymentDetails['token']);
        $paymentDetails['token'] = TokenSanitizer::sanitizeToken($paymentDetails['token']);
        if (!TokenValidator::validateToken($paymentDetails['token'])) {
            return false;
        }

        $dedodedPaymentToken = TokenTool::decodeAndCheckToken($paymentDetails['token'], 'paypalredirectok');
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
        $message = '¡Gracias por elegir volar con Destiny Airlines! Confirmamos que hemos recibido su pago y su reserva está confirmada.

        Adjuntamos a este correo electrónico la factura de su viaje. Le recomendamos que la guarde para sus registros.
        
        Si tiene alguna pregunta o necesita más información, no dude en ponerse en contacto con nosotros.
        
        ¡Esperamos verle a bordo pronto!
        
        Saludos cordiales,
        El equipo de Destiny Airlines';

        //Generamos las facturas con los id que contiene el token válido
        $invoiceDepartureData = $invoiceTool->generateInvoiceData($dedodedPaymentToken['response']->data->idUser, $dedodedPaymentToken['response']->data->idInvoiceD);
        $invoiceDepartureHtml = $invoiceTool->generateInvoiceHtml($invoiceDepartureData);
        $invoiceDeparturePdf = $pdfTool->generatePdfFromHtml($invoiceDepartureHtml);
        $emailTool->sendEmail(
            [
                'toEmail' => $invoiceDepartureData['userData']['emailAddress'],
                'fromEmail' => $cfgOriginEmailIni['email'],
                'fromPassword' => $cfgOriginEmailIni['password'],
                'subject' => $subject,
                'message' => $message
            ],
            'invoiceTemplate',
            $invoiceDeparturePdf,
            'invoice'
        );

        $invoiceReturnData;
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
                    'subject' => $subject,
                    'message' => $message
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
