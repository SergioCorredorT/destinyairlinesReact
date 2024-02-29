<?php
require_once ROOT_PATH . '/DataProcessing/ProcessData.php';
abstract class BaseFilter
{
    protected $processData = null;
    protected $iniTool;

    public function __construct()
    {
        $this->processData = new ProcessData();

        require_once ROOT_PATH . '/Tools/IniTool.php';
        $this->iniTool = new IniTool(ROOT_PATH  . '/Config/cfg.ini');

        spl_autoload_register(function (string $class_name) {

            // Buscar en la carpeta de herramientas
            $toolFile = ROOT_PATH . '/Tools/' . $class_name . '.php';

            if (file_exists($toolFile)) {
                require_once $toolFile;
            }
        });
    }
}
