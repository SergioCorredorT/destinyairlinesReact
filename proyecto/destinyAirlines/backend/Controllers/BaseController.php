<?php
abstract class BaseController
{
    public function __construct()
    {
        spl_autoload_register(function ($class_name) {

            $file = './models/' . $class_name . '.php';

            if (file_exists($file)) {

                require_once $file;
            }
        });
    }
}
