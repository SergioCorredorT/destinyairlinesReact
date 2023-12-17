<?php
$command = $_POST['command'] ?? $_GET['command'] ?? '';
define('ROOT_PATH', __DIR__);

function executeCommand(string $controllerName, string $methodName, array $params): array
{
    try {
        $controller = new $controllerName();
        $response = $controller->$methodName($params);

        if (is_array($response) && array_keys($response) !== range(0, count($response) - 1)) {
            // Si $response es un array o array asociativo, fusionamos $response con el array que se pasa a json_encode
            return array_merge(['status' => true], $response);
        } else {
            return ['status' => true, 'response' => $response];
        }
    } catch (Exception $e) {
        error_log('Catched error: ' . $e);
        return ['status' => false, 'response' => false];
    }
}

$controllers = [
    //POST
    'checkupdateaccesstoken'                    => ['controller' => 'TokenController',          'method' => 'checkUpdateAccessToken'],
    'checkupdaterefreshtoken'                   => ['controller' => 'TokenController',          'method' => 'checkUpdateRefreshToken'],
    'contact'                                   => ['controller' => 'ContactController',        'method' => 'sendContact'],
    'createuser'                                => ['controller' => 'UserController',           'method' => 'createUser'],
    'updateuser'                                => ['controller' => 'UserController',           'method' => 'updateUser'],
    'removeuser'                                => ['controller' => 'UserController',           'method' => 'deleteUser'],
    'loginuser'                                 => ['controller' => 'UserController',           'method' => 'loginUser'],
    'logoutuser'                                => ['controller' => 'UserController',           'method' => 'logoutUser'],
    'passwordreset'                             => ['controller' => 'UserController',           'method' => 'passwordReset'],
    'forgotpassword'                            => ['controller' => 'UserController',           'method' => 'forgotPassword'],
    'storeflightdetails'                        => ['controller' => 'BookController',           'method' => 'storeFlightDetails'],
    'storepassengerdetails'                     => ['controller' => 'BookController',           'method' => 'storePassengerDetails'],
    'storebookservicesdetails'                  => ['controller' => 'BookController',           'method' => 'storeBookServicesDetails'],
    'storeprimarycontactinformationdetails'     => ['controller' => 'BookController',           'method' => 'storePrimaryContactInformationDetails'],
    'paymentdetails'                            => ['controller' => 'BookController',           'method' => 'paymentDetails'],
    'checkin'                                   => ['controller' => 'BookController',           'method' => 'checkin'],
    'getsummarybooks'                           => ['controller' => 'BookController',           'method' => 'getSummaryBooks'],
    'getbookinfo'                               => ['controller' => 'BookController',           'method' => 'getBookInfo'],
    'cancelbook'                                => ['controller' => 'BookController',           'method' => 'cancelBook'],
    'getassistivedevices'                       => ['controller' => 'OptionsController',        'method' => 'getAssistiveDevices'],
    'getmedicalequipments'                      => ['controller' => 'OptionsController',        'method' => 'getMedicalEquipments'],
    'getmobilitylimitations'                    => ['controller' => 'OptionsController',        'method' => 'getMobilityLimitations'],
    'getcommunicationneeds'                     => ['controller' => 'OptionsController',        'method' => 'getCommunicationNeeds'],
    'getmedicationrequirements'                 => ['controller' => 'OptionsController',        'method' => 'getMedicationRequirements'],
    'getdoctypes'                               => ['controller' => 'OptionsController',        'method' => 'getDocTypes'],
    'getagecategories'                          => ['controller' => 'OptionsController',        'method' => 'getAgeCategories'],
    //GET
    'gotopasswordreset'                         => ['controller' => 'passwordResetController',  'method' => 'goToPasswordReset'],
    'paypalredirectok'                          => ['controller' => 'paymentController',        'method' => 'paypalRedirectOk'],
    'paypalredirectcancel'                      => ['controller' => 'paymentController',        'method' => 'paypalRedirectCancel'],
    //SOLO PARA DEBUG
    'obtenervariablesdesesiondebug'             => ['controller' => 'DebugController',          'method' => 'obtenerVariablesDeSesionDebug'],
    'debugpaypalredirectok'                     => ['controller' => 'paymentController',        'method' => 'debugPaypalRedirectOk']
];

if (array_key_exists(strtolower($command), $controllers)) {
    $controllerInfo = $controllers[strtolower($command)];
    require_once "./Controllers/{$controllerInfo['controller']}.php";
    $params = $_SERVER['REQUEST_METHOD'] === 'POST' ? $_POST : $_GET;
    echo json_encode(executeCommand($controllerInfo['controller'], $controllerInfo['method'], $params));
} else {
    echo json_encode(['status' => true, 'response' => false]);
}
