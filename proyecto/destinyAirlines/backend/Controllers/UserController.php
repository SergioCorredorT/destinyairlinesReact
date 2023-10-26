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

    public function updateUser($POST)
    {
        require_once './Tools/TokenTool.php';

        return true;
    }

    public function deleteUser($POST)
    {
        require_once './Tools/TokenTool.php';
        $userData = [
            'emailAddress'          => $POST['emailAddress'] ?? "",
            'password'              => $POST['password'] ?? "",
            'token'                 => $POST['token'] ?? "",
            'dateTime'  => date('Y-m-d H:i:s')
        ];
        $decodedToken = TokenTool::decodeAndCheckToken($userData["token"]);
        if ($decodedToken && $decodedToken->data->email === $POST['emailAddress']) {
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
            //Comprobar password
            $UserModel = new UserModel();
            $results = $UserModel->readUserByEmailPassword($userData["emailAddress"], $userData["password"]);
            $data=[
                "id" => $results[0]["id_USERS"],
                "email" => $results[0]["emailAddress"]
            ];
            if ($results) {
                $token = TokenTool::generateToken($data);
                return $token;
            }
        }

        return false;
    }

    public function logoutUser($POST)
    {
        require_once './Tools/TokenTool.php';

        $userData = [
            'token'          => $POST['token'] ?? "",
            'dateTime'       => date('Y-m-d H:i:s')
        ];
        if (TokenTool::decodeAndCheckToken($userData["token"])) {
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
