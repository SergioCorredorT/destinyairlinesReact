<?php
require_once ROOT_PATH . '/Controllers/BaseController.php';
final class DebugController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function obtenerVariablesDeSesionDebug()
    {
        return SessionTool::getAll();
    }

    public function generarFactura()
    {
        $invoiceTool= new InvoiceTool();
        $invoiceTool->generateInvoiceData(138,25);
    }
    
    public function debugPaypalRedirectOk()
    {
        require_once ROOT_PATH . '/Controllers/PaymentController.php';
        $tokenSettings = $this->iniTool->getKeysAndValues('tokenSettings');

        //CREAR TOKEN de 3 horas (caducidad de paypal en su web)
        $data = ['id' => 138, 'idUser' => 138, 'idInvoiceD' => 30, 'type' => 'paypalredirectok'];
        $paymentToken = TokenTool::generateToken($data, intval($tokenSettings['secondsTimeLifePaymentReturnUrl']));
        $PaymentController = new PaymentController();
        return $PaymentController->paypalRedirectOk(['token' => $paymentToken]);
    }
}
