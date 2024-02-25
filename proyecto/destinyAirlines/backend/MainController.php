<?php
declare(strict_types=1);

//Esta parte debería funcionar si el usuario ejecutor de Apache tiene permisos para modificar el archivo api.bucket de la raíz del backend
require './vendor/autoload.php';

use bandwidthThrottle\tokenBucket\Rate;
use bandwidthThrottle\tokenBucket\TokenBucket;
use bandwidthThrottle\tokenBucket\storage\FileStorage;
use bandwidthThrottle\tokenBucket\storage\StorageException;

try {
    $storage = new FileStorage(__DIR__ . "/api.bucket");
    $rate    = new Rate(5, Rate::SECOND);
    $bucket  = new TokenBucket(10, $rate, $storage);
    $bucket->bootstrap(10);

    $seconds = 0;
    if (!$bucket->consume(1, $seconds)) {
        http_response_code(429);
        header(sprintf("Retry-After: %d", floor($seconds)));
        exit();
    }
} catch (StorageException $e) {
    error_log("Error al escribir en el almacenamiento: " . $e->getMessage());
}

define('ROOT_PATH', __DIR__);
require_once ROOT_PATH . '/Tools/IniTool.php';
$iniTool = new IniTool(ROOT_PATH  . '/Config/cfg.ini');
$requestConfiguration = $iniTool->getKeysAndValues('requestConfiguration');
//Para averiguar el dominio de la petición recibida
//error_log('Access-Control-Allow-Origin needed: ' . $_SERVER['HTTP_ORIGIN']);

// Configuración de la petición permitida
// Establece la cabecera 'Access-Control-Allow-Origin' para permitir peticiones desde un origen específico
header('Access-Control-Allow-Origin: ' . $requestConfiguration["accessControlAllowOrigin"]);

// Opcional: Establece la cabecera 'Access-Control-Allow-Methods' para permitir métodos HTTP específicos como GET, POST, PUT, DELETE
header('Access-Control-Allow-Methods: ' . $requestConfiguration["accessControlAllowMethods"]);

// Opcional: Establece la cabecera 'Access-Control-Allow-Headers' para permitir cabeceras HTTP específicas
header('Access-Control-Allow-Headers: ' . $requestConfiguration["accessControlAllowHeaders"]);

// En la siguiente línea se recogen los datos recibidos mediante JSON, de forma que se pueden recibir JSON, GET y POST
$data = json_decode(file_get_contents('php://input'), true);
$command = $data['command'] ?? $_POST['command'] ?? $_GET['command'] ?? '';

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
    'getupdatetime'                             => ['controller' => 'TokenController',          'method' => 'getUpdateTime'],
    'contact'                                   => ['controller' => 'ContactController',        'method' => 'sendContact'],
    'getusereditableinfo'                       => ['controller' => 'UserController',           'method' => 'getUserEditableInfo'],
    'createuser'                                => ['controller' => 'UserController',           'method' => 'createUser'],
    'updateuser'                                => ['controller' => 'UserController',           'method' => 'updateUser'],
    'updatepassword'                            => ['controller' => 'UserController',           'method' => 'updatePassword'],
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
    'getdoctypeses'                             => ['controller' => 'OptionsController',        'method' => 'getDocTypesEs'],
    'getagecategories'                          => ['controller' => 'OptionsController',        'method' => 'getAgeCategories'],
    'getoptions'                                => ['controller' => 'OptionsController',        'method' => 'getOptions'],


    'getcompanyinfo'                            => ['controller' => 'PageDetailsController',    'method' => 'getCompanyInfo'],
    //GET
    'gotopasswordreset'                         => ['controller' => 'EmailLinkActionController', 'method' => 'goToPasswordReset'],
    'gotoemailverification'                     => ['controller' => 'EmailLinkActionController', 'method' => 'goToEmailVerification'],
    'gotoaccountdeletion'                       => ['controller' => 'EmailLinkActionController', 'method' => 'goToAccountDeletion'],
    'paypalredirectok'                          => ['controller' => 'PaymentController',        'method' => 'paypalRedirectOk'],
    'paypalredirectcancel'                      => ['controller' => 'PaymentController',        'method' => 'paypalRedirectCancel'],
    //SOLO PARA DEBUG
    'obtenervariablesdesesiondebug'             => ['controller' => 'DebugController',          'method' => 'obtenerVariablesDeSesionDebug'],
    'debugpaypalredirectok'                     => ['controller' => 'DebugController',          'method' => 'debugPaypalRedirectOk']
];

if (array_key_exists(strtolower($command), $controllers)) {
    $controllerInfo = $controllers[strtolower($command)];
    require_once "./Controllers/{$controllerInfo['controller']}.php";
    //$params = $_SERVER['REQUEST_METHOD'] === 'POST' ? $_POST : $_GET;

    //El siguiente bloque de código es para discriminar el método HTTP, incluso si es JSON crudo por POST, y ejecutar la función correspondiente
    $params;
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_SERVER["CONTENT_TYPE"]) && strpos($_SERVER["CONTENT_TYPE"], "application/json") !== false) {
            $json = file_get_contents('php://input');
            $params = json_decode($json, true);
        } else {
            $params = $_POST;
        }
    } else {
        $params = $_GET;
    }

    echo json_encode(executeCommand($controllerInfo['controller'], $controllerInfo['method'], $params));
} else {
    echo json_encode(['status' => true, 'response' => false]);
}
