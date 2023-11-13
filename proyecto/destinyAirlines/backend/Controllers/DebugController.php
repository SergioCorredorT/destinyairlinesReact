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
}
