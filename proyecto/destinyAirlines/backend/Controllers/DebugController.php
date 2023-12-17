<?php
require_once ROOT_PATH . '/Controllers/BaseController.php';
require_once ROOT_PATH . '/Tools/SessionTool.php';
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
        require_once ROOT_PATH . '/Tools/InvoiceTool.php';
        $invoiceTool= new InvoiceTool();
        $invoiceTool->generateInvoiceData(138,25);
    }
    
    public function debugPaypalRedirectOk()
    {
        require_once ROOT_PATH . '/Tools/TokenTool.php';
        require_once ROOT_PATH . '/Tools/IniTool.php';
        require_once ROOT_PATH . '/Controllers/PaymentController.php';
        $iniTool = new IniTool(ROOT_PATH  . '/Config/cfg.ini');
        $tokenSettings = $iniTool->getKeysAndValues('tokenSettings');

        //CREAR TOKEN de 3 horas (caducidad de paypal en su web)
        $data = ['id' => 138, 'idUser' => 138, 'idInvoiceD' => 30, 'type' => 'paypalredirectok'];
        $paymentToken = TokenTool::generateToken($data, intval($tokenSettings['secondsTimeLifePaymentReturnUrl']));
        $PaymentController = new PaymentController();
        return $PaymentController->paypalRedirectOk(['token' => $paymentToken]);
    }
}
