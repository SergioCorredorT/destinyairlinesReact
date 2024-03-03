<?php
require_once ROOT_PATH . '/DataProcessing/ProcessData.php';
abstract class BaseController
{
    protected $processData = null;
    protected $iniTool;
    protected $filter;

    public function __construct()
    {
        $this->processData = new ProcessData();

        require_once ROOT_PATH . '/Tools/IniTool.php';
        $this->iniTool = new IniTool(ROOT_PATH  . '/Config/cfg.ini');

        spl_autoload_register(function (string $class_name) {

            // Buscar en la carpeta de modelos
            $modelFile = ROOT_PATH . '/Models/' . $class_name . '.php';

            // Buscar en la carpeta de herramientas
            $toolFile = ROOT_PATH . '/Tools/' . $class_name . '.php';

            if (file_exists($modelFile)) {
                require_once $modelFile;
            } elseif (file_exists($toolFile)) {
                require_once $toolFile;
            }
        });
    }

    public function loadFilter(string $nameFilter): void
    {
        try {
            $fileName = ucfirst($nameFilter) . 'Filter';
            $filePath = ROOT_PATH . '/DataProcessing/Filters/' . $fileName . '.php';

            if (!file_exists($filePath)) {
                throw new Exception("Filter file does not exist: $filePath");
            }

            require_once $filePath;

            if (!class_exists($fileName)) {
                throw new Exception("Filter class does not exist: $fileName");
            }

            $this->filter = new $fileName();
        } catch (Exception $e) {
            $this->filter = null;
        }
    }
}
