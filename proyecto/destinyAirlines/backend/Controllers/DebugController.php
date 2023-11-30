<?php
require_once './Controllers/BaseController.php';
require_once './Tools/SessionTool.php';
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
        require_once './Tools/InvoiceTool.php';
        $invoiceTool= new InvoiceTool();
        $invoiceTool->generateInvoiceData(138,25);
    }
    
}
