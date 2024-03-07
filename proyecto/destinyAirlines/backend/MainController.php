<?php
declare(strict_types=1);
define('ROOT_PATH', __DIR__);
require ROOT_PATH . '/Tools/InitialTools.php';
$initialTools = new InitialTools();
$initialTools->RequestValidator();    //Esta parte debería funcionar si el usuario ejecutor de Apache tiene permisos para modificar el archivo api.bucket de la raíz del backend
$initialTools->setCorsHeaders();

$controllers = require ROOT_PATH . '/ControllerDefinitions.php';

$command = $initialTools->getCommand();
echo $initialTools->executeAndRespond($command, $controllers);
