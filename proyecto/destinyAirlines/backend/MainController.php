<?php
$command = $_POST['command'] ?? "";

function executeCommand($controllerName, $methodName, $params) {
    try {
        $controller = new $controllerName();
        $response = $controller->$methodName($params);
        echo json_encode(['status' => true, 'response' => $response]);
    } catch (Exception $e) {
        echo json_encode(['status' => false, 'response' => false]);
        error_log('Catched error: ' . $e);
    }
}

$controllers = [
    'contact'       => ['ContactController', 'sendContact'],
    'createuser'    => ['UserController', 'createUser'],
    'updateuser'    => ['UserController', 'updateUser'],
    'removeuser'    => ['UserController', 'deleteUser'],
    'loginuser'     => ['UserController', 'loginUser'],
    'logoutuser'    => ['UserController', 'logoutUser']
];

if (array_key_exists(strtolower($command), $controllers)) {
    [$controllerName, $methodName] = $controllers[strtolower($command)];
    require_once "./Controllers/$controllerName.php";
    executeCommand($controllerName, $methodName, $_POST);
} else {
    echo json_encode(['status' => true, 'response' => false]);
}
