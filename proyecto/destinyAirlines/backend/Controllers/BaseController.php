<?php
abstract class BaseController
{
    public function __construct()
    {
        spl_autoload_register(function (string $class_name) {

            $file = ROOT_PATH . '/models/' . $class_name . '.php';

            if (file_exists($file)) {

                require_once $file;
            }
        });
    }
}
