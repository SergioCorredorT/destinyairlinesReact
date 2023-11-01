<?php
$command = $_POST['command'] ?? "";

function executeCommand($controllerName, $methodName, $params)
{
    try {
        $controller = new $controllerName();
        $response = $controller->$methodName($params);

        if (is_array($response) && array_keys($response) !== range(0, count($response) - 1)) {
            // Si $response es un array o array asociativo, fusionamos $response con el array que se pasa a json_encode
            echo json_encode(array_merge(['status' => true], $response));
        } else {
            echo json_encode(['status' => true, 'response' => $response]);
        }
    } catch (Exception $e) {
        echo json_encode(['status' => false, 'response' => false]);
        error_log('Catched error: ' . $e);
    }
}

$controllers = [
    'contact'       => ['controller' => 'ContactController',    'method' => 'sendContact'],
    'createuser'    => ['controller' => 'UserController',       'method' => 'createUser'],
    'updateuser'    => ['controller' => 'UserController',       'method' => 'updateUser'],
    'removeuser'    => ['controller' => 'UserController',       'method' => 'deleteUser'],
    'loginuser'     => ['controller' => 'UserController',       'method' => 'loginUser'],
    'logoutuser'    => ['controller' => 'UserController',       'method' => 'logoutUser'],
    'passwordreset' => ['controller' => 'UserController',       'method' => 'passwordReset']
];

if (array_key_exists(strtolower($command), $controllers)) {
    $controllerInfo = $controllers[strtolower($command)];
    require_once "./Controllers/{$controllerInfo['controller']}.php";
    executeCommand($controllerInfo['controller'], $controllerInfo['method'], $_POST);
} else {
    echo json_encode(['status' => true, 'response' => false]);
}
