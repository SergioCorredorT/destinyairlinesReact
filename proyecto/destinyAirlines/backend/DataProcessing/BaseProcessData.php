<?php
abstract class BaseProcessData
{
    protected $iniTool;

    public function __construct()
    {
        require_once ROOT_PATH . '/Tools/IniTool.php';
        $this->iniTool = new IniTool(ROOT_PATH  . '/Config/cfg.ini');

        spl_autoload_register(function (string $class_name) {
            // Buscar en la carpeta de modelos
            $sanitizerFile = ROOT_PATH . '/DataProcessing/Sanitizers/' . $class_name . '.php';

            // Buscar en la carpeta de herramientas
            $validatorFile = ROOT_PATH . '/DataProcessing/Validators/' . $class_name . '.php';

            if (file_exists($sanitizerFile)) {
                require_once $sanitizerFile;
            } elseif (file_exists($validatorFile)) {
                require_once $validatorFile;
            }
        });
    }
}
