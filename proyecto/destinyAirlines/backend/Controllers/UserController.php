<?php
/*
Login, editar usuario, eliminar cuenta

tras logueo y envío de token al frontend
 en el frontend entonces se creará un localstorage con ese token para enviarlo al server cuando sea necesario.
	Entonces cuando el usuario haga una acción en la que sea necesaria una comprobación de servidor, el frontend enviará al php el token, el cual comprobará con secretPass

limitar intentos fallidos de sesión
*/
require_once './Controllers/BaseController.php';
require_once './Sanitizers/UserSanitizer.php';
require_once './Validators/UserValidator.php';
require_once "./Tools/IniTool.php";
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
            'phoneNumber1'          => $POST['phoneNumber1'] ?? "",
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

    public function updateUser($POST)
    {
//POR AHORA SE MODIFICAN TODOS ya que recibo "" del form y estos se envían al update, averiguar como discriminar óptimamente.
        $userData = [
            'title'                 => $POST['title'] ?? null,
            'firstName'             => $POST['firstName'] ?? null,
            'lastName'              => $POST['lastName'] ?? null,
            'townCity'              => $POST['townCity'] ?? null,
            'streetAddress'         => $POST['streetAddress'] ?? null,
            'zipCode'               => $POST['zipCode'] ?? null,
            'country'               => $POST['country'] ?? null,
            'emailAddress'          => $POST['emailAddress'] ?? null,
            'password'              => $POST['password'] ?? null,
            'phoneNumber1'          => $POST['phoneNumber1'] ?? null,
            'phoneNumber2'          => $POST['phoneNumber2'] ?? null,
            'phoneNumber3'          => $POST['phoneNumber3'] ?? null,
            'companyName'           => $POST['companyName'] ?? null,
            'companyTaxNumber'      => $POST['companyTaxNumber'] ?? null,
            'companyPhoneNumber'    => $POST['companyPhoneNumber'] ?? null,
            'refreshToken'          => $POST['refreshToken'] ?? null,
            'accessToken'           => $POST['accessToken'] ?? null,
            'dateTime'  => date('Y-m-d H:i:s')
        ];

        require_once './Tools/TokenTool.php';
        $iniTool = new IniTool('./Config/cfg.ini');
        $tokenSettings = $iniTool->getKeysAndValues("tokenSettings");
        $secondsMinTimeLifeAccessToken=intval($tokenSettings["secondsMinTimeLifeAccessToken"]);
        $secondsMinTimeLifeRefreshToken=intval($tokenSettings["secondsMinTimeLifeRefreshToken"]);
        $secondsMaxTimeLifeAccessToken=intval($tokenSettings["secondsMaxTimeLifeAccessToken"]);
        $secondsMaxTimeLifeRefreshToken=intval($tokenSettings["secondsMaxTimeLifeRefreshToken"]);
//DEVOLVER TOKENS AL FRONT DESDE EL RETURN
        TokenTool::checkUpdateByRemainingTokenTimes($userData["accessToken"], $userData["refreshToken"],$secondsMinTimeLifeAccessToken,$secondsMinTimeLifeRefreshToken,$secondsMaxTimeLifeAccessToken,$secondsMaxTimeLifeRefreshToken);
        $decodedToken = TokenTool::decodeAndCheckToken($userData["accessToken"]);
        $email = $UserModel->getEmailById($decodedToken->data->id);

        if ($decodedToken && $email === $POST['emailAddress']) {
            $userData = UserSanitizer::sanitize($userData);
            if (UserValidator::validate($userData)) {
                $UserModel = new UserModel();
                if ($UserModel->updateUsersByEmail(array_filter($userData), $email)) {
                    return true;
                }
            }
        }
        return false;
    }

    public function deleteUser($POST)
    {
        //eliminar tokens en el frontend
        require_once './Tools/TokenTool.php';
        $userData = [
            'emailAddress'          => $POST['emailAddress'] ?? "",
            'password'              => $POST['password'] ?? "",
            'refreshToken'          => $POST['refreshToken'] ?? "",
            'dateTime'  => date('Y-m-d H:i:s')
        ];
        $decodedToken = TokenTool::decodeAndCheckToken($userData["refreshToken"]);
        $UserModel = new UserModel();
        $email = $UserModel->getEmailById($decodedToken->data->id);

        if ($decodedToken && $email === $POST['emailAddress']) {
            require_once './Tools/SessionTool.php';
            SessionTool::destroy();

            $userData = UserSanitizer::sanitize($userData);
            if (UserValidator::validate($userData)) {
                $UserModel = new UserModel();
                if ($UserModel->deleteUserByEmailAndPassword($userData["emailAddress"], $userData["password"])) {
                    return true;
                }
            }
        }
        return false;
    }

    public function loginUser($POST)
    {
        require_once './Tools/TokenTool.php';
        $userData = [
            'emailAddress'          => $POST['emailAddress'] ?? "",
            'password'              => $POST['password'] ?? "",
            'dateTime'  => date('Y-m-d H:i:s')
        ];
        $userData = UserSanitizer::sanitize($userData);
        $isValidate = UserValidator::validate($userData);
        if ($isValidate) {
            $UserModel = new UserModel();
            $results = $UserModel->readUserByEmailPassword($userData["emailAddress"], $userData["password"]);
            $data = [
                "id" => $results[0]["id_USERS"]
            ];
            if ($results) {
                /*
                    Destiny Airlines
                                LifeTime    minTime
                    Refresh     3 días ,    1 día
                    Access      1 hora,     30 min
                */
                $iniTool = new IniTool('./Config/cfg.ini');
                $tokenSettings = $iniTool->getKeysAndValues("tokenSettings");
                $secondsMaxTimeLifeAccessToken=intval($tokenSettings["secondsMaxTimeLifeAccessToken"]);
                $secondsMaxTimeLifeRefreshToken=intval($tokenSettings["secondsMaxTimeLifeRefreshToken"]);

                $accessToken = TokenTool::generateToken($data, $secondsMaxTimeLifeAccessToken);
                $refreshToken = TokenTool::generateToken($data, $secondsMaxTimeLifeRefreshToken);
                return ["accessToken" => $accessToken, "refreshToken" => $refreshToken];
            }
        }

        return false;
    }

    public function logoutUser($POST)
    {
        require_once './Tools/TokenTool.php';

        $userData = [
            'refreshToken'           => $POST['refreshToken'] ?? "",
            'dateTime'              => date('Y-m-d H:i:s')
        ];
        if (TokenTool::decodeAndCheckToken($userData["refreshToken"])) {
            //eliminar tokens en el frontend
            //Un método más seguro es hacer en server una lista blanca o negra para dejar pasar solo a los refresh tokens adecuados, pero requeriría carga en el server, y se desaprovecha la ventaja de no hacer un logueo basado en session
            require_once './Tools/SessionTool.php';
            SessionTool::destroy();
            return true;
        }
        return false;
    }
    /*
    private function generateTokenSession($user)
    {//OLD, la autenticación basada en sesiones es otro modo (sobrecarga el server y no sirve para varias API a la vez porque es necesaria una session en cada una, aunque tiene otros modos seguros),
        // para este proyecto prefiero la autenticación basada en tokens
        $token = hash('sha256', uniqid(mt_rand(), true));
        SessionTool::startSession("destinyAirlines_session", 60 * 60 * 10);
        $_SESSION['login'] = ['token' => $token, 'id' => $user["id_USERS"]];
        return $token;
    }
*/
}
