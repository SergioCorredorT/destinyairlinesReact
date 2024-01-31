<?php
abstract class BaseController
{
    protected $iniTool;

    public function __construct()
    {
        require_once ROOT_PATH . '/Tools/IniTool.php';
        $this->iniTool = new IniTool(ROOT_PATH  . '/Config/cfg.ini');

        spl_autoload_register(function (string $class_name) {

            // Buscar en la carpeta de modelos
            $modelFile = ROOT_PATH . '/models/' . $class_name . '.php';

            // Buscar en la carpeta de herramientas
            $toolFile = ROOT_PATH . '/tools/' . $class_name . '.php';

            if (file_exists($modelFile)) {
                require_once $modelFile;
            } elseif (file_exists($toolFile)) {
                require_once $toolFile;
            }
        });
    }
}
