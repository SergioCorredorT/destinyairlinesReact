<?php
/*
Login, editar usuario, eliminar cuenta
pass encriptadas con hash

para hacer un login en php,
los campos email y password que rellenó el usuario en un form se envían por POST a un archivo php mediante axios (desde react js),
entonces en el php saneo y valido los datos, compruebo password con hash bbdd, para seleccionar si en bbdd tengo ese email+hasPassword,
	si no lo tengo lanzo exception
	si lo tengo entonces debo crear un $_SESSION con el id del usuario y un token
		el token se crea mediante jsonwebtoken con jwt.sign(idUsuario, claveServerGuardadaEnElIni)
, entonces el token se le envía al usuario con echo,
 en el frontend entonces se creará un localstorage con ese token para enviarlo al server cuando sea necesario.
	Entonces cuando el usuario haga una acción en la que sea necesaria una comprobación de servidor, el frontend enviará al php el token, el cual debo compararlo con el token guardado en $_SESSION para saber si es el mismo usuario. 

limitar intentos fallidos de sesión
*/
require_once './Controllers/BaseController.php';
require_once './Sanitizers/UserSanitizer.php';
require_once './Validators/UserValidator.php';
final class UserController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function createUser($POST)
    {
        $userData = [
            'title'                 => $POST['title'] ?? "",
            'firstName'             => $POST['firstName'] ?? "",
            'lastName'              => $POST['lastName'] ?? "",
            'townCity'              => $POST['townCity'] ?? "",
            'streetAddress'         => $POST['streetAddress'] ?? "",
            'zipCode'               => $POST['zipCode'] ?? "",
            'country'               => $POST['country'] ?? "",
            'emailAddress'          => $POST['emailAddress'] ?? "",
            'password'              => $POST['password'] ?? "",
            'phoneNumber1'          => $POST['phoneNumber1	'] ?? "",
            'phoneNumber2'          => $POST['phoneNumber2'] ?? "",
            'phoneNumber3'          => $POST['phoneNumber3'] ?? "",
            'companyName'           => $POST['companyName'] ?? "",
            'companyTaxNumber'      => $POST['companyTaxNumber'] ?? "",
            'companyPhoneNumber'    => $POST['companyPhoneNumber'] ?? "",
            'dateTime'  => date('Y-m-d H:i:s')
        ];

        $userData = UserSanitizer::sanitize($userData);
        $isValidate = UserValidator::validate($userData);
        if ($isValidate) {
            $userData["passwordHash"] = password_hash($userData["password"], PASSWORD_BCRYPT);
            unset($userData["password"]);

            $UserModel = new UserModel();
            if ($UserModel->createUser($userData)) {
                return true;
            }
        }
        return false;
    }

    public function deleteUser($POST)
    {
        //Comprobar decodificación del token recibido
        //if($_SESSION["userEmail"]=== $POST['emailAddress'])
        $userData = [
            'emailAddress'          => $POST['emailAddress'] ?? "",
            'password'              => $POST['password'] ?? "",
            'dateTime'  => date('Y-m-d H:i:s')
        ];
        $userData = UserSanitizer::sanitize($userData);
        if (UserValidator::validate($userData)) {
            $UserModel = new UserModel();
            if ($UserModel->deleteUserByEmailAndPassword($userData["emailAddress"], $userData["password"])) {
                return true;
            }
        }
        return false;
    }

    public function loginUser($POST)
    {
        require_once './Tools/SessionTool.php';
        $userData = [
            'emailAddress'          => $POST['emailAddress'] ?? "",
            'password'              => $POST['password'] ?? "",
            'dateTime'  => date('Y-m-d H:i:s')
        ];
        $userData = UserSanitizer::sanitize($userData);
        $isValidate = UserValidator::validate($userData);
        if ($isValidate) {
            //Comprobar password
            $UserModel = new UserModel();
            $results = $UserModel->readUserByEmailPassword($userData["emailAddress"], $userData["password"]);

            if ($results) {
                $token = $this->generateToken($results[0]);
                return $token;
            }
        }

        return false;
    }

    public function logoutUser()
    {
        //Comprobar token recibido
        $_SESSION = array();
        SessionTool::destroy();
        return true;
    }

    private function generateTokenSession($user)
    {//OLD, la autenticación basada en sesiones es otro modo (sobrecarga el server y no sirve para varias API a la vez porque es necesaria session en cada una, aunque tiene otros modos seguros),
        // para este proyecto prefiero la autenticación basada en tokens
        $token = hash('sha256', uniqid(mt_rand(), true));
        SessionTool::startSession("destinyAirlines_session", 60 * 60 * 10);
        $_SESSION['login'] = ['token' => $token, 'id' => $user["id_USERS"]];
        return $token;
    }

    private function generateToken($user)
    {
        //Sería buena idea cortar el generar token y decodificarlo en otra api, estando el secret en una variable de entorno donde estén ambas funciones
        require_once "./vendor/autoload.php";

        $iniTool = new IniTool('./Config/cfg.ini');
        [$secret] = $iniTool->getKeysAndValues("secretTokenPassword"); //Normalmente se recogería de una variable de entorno desde $_ENV

        $payload = array(
            "iss" => "destinyAirlines", // Emisor del token
            "aud" => "destinyAirlines", // Destinatario del token
            "iat" => time(), // Tiempo que inició el token
            "exp" => time() + (60 * 60), // Tiempo que expirará el token (+1 hora)
            "data" => [ // información adicional que se quiera añadir
                "id" => $user["id_USERS"],
                "email" => $user["emailAddres"]
            ],
            "role" => "user"
        );

        $jwt = \Firebase\JWT\JWT::encode($payload, $secret, 'HS256');
        return $jwt;
    }

    private function decodificarToken($jwt)
    {
        $iniTool = new IniTool('./Config/cfg.ini');
        [$secret] = $iniTool->getKeysAndValues("secretTokenPassword"); // Normalmente se recogería de una variable de entorno desde $_ENV

        try {
            $headers = new stdClass();
            $headers->alg = 'HS256';
            $decoded = \Firebase\JWT\JWT::decode($jwt, $secret, $headers);
            return $decoded;
        } catch (\Firebase\JWT\ExpiredException $e) {
            // El token ha expirado
            echo 'El token ha expirado';
        } catch (\Firebase\JWT\SignatureInvalidException $e) {
            // La firma del token no coincide con la clave proporcionada
            echo 'La firma del token no coincide con la clave proporcionada';
        } catch (\Exception $e) {
            // Otro error
            echo 'Ha ocurrido un error al decodificar el token: ' . $e->getMessage();
        }
    }
}
