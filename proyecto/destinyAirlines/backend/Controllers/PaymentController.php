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
        require_once './Tools/PdfTool.php';
        require_once './Tools/EmailTool.php';

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
        $pdfTool = new PdfTool();
        $emailTool = new EmailTool();
        $iniTool = new IniTool('./Config/cfg.ini');
        $cfgOriginEmailIni = $iniTool->getKeysAndValues("originEmail");

        //Generamos las facturas con los id que contiene el token válido
        $invoiceDepartureData = $invoiceTool->generateInvoiceData($dedodedPaymentToken['response']->data->idUser, $dedodedPaymentToken['response']->data->idInvoiceD);
        $invoiceDepartureHtml = $invoiceTool->generateInvoiceHtml($invoiceDepartureData);
        $invoiceDeparturePdf = $pdfTool->generatePdfFromHtml($invoiceDepartureHtml);
        $emailTool->sendEmail(
            [
                'toEmail' => $invoiceDepartureData['userData']['emailAddress'],
                'fromEmail' => $cfgOriginEmailIni['email'],
                'fromPassword' => $cfgOriginEmailIni['password'],
                'subject' => 'Factura de su nuevo viaje',
                'message' => '¡Gracias por elegir volar con Destiny Airlines! Confirmamos que hemos recibido su pago y su reserva está confirmada.

                Adjuntamos a este correo electrónico la factura de su viaje. Le recomendamos que la guarde para sus registros.
                
                Si tiene alguna pregunta o necesita más información, no dude en ponerse en contacto con nosotros.
                
                ¡Esperamos verle a bordo pronto!
                
                Saludos cordiales,
                El equipo de Destiny Airlines'
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
                    'subject' => 'Factura de su nuevo viaje',
                    'message' => '¡Gracias por elegir volar con Destiny Airlines! Confirmamos que hemos recibido su pago y su reserva está confirmada.

                    Adjuntamos a este correo electrónico la factura de su viaje. Le recomendamos que la guarde para sus registros.
                    
                    Si tiene alguna pregunta o necesita más información, no dude en ponerse en contacto con nosotros.
                    
                    ¡Esperamos verle a bordo pronto!
                    
                    Saludos cordiales,
                    El equipo de Destiny Airlines'
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
        header('Location: ./Views/cancelPaymentPage.html');
        exit;
    }
}
