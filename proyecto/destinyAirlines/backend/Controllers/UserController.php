<?php
/*
Login, editar usuario, eliminar cuenta
pass encriptadas con hash

para hacer un login en php,
los campos email y password que rellenó el usuario en un form se envían por POST a un archivo php mediante axios (desde react js),
entonces en el php saneo y valido los datos, aplico un hash al password para posteriormente comprobar si en la bbdd de mysql tengo en la tabla users un registro con ese email y password,
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
class UserController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function createUser($POST)
    {
        require_once './Sanitizers/UserSanitizer.php';
        require_once './Validators/UserValidator.php';

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

    public function loginUser($POST)
    {
        $userData = [
            'emailAddress'          => $POST['emailAddress'] ?? "",
            'password'              => $POST['password'] ?? "",
            'dateTime'  => date('Y-m-d H:i:s')
        ];
        $userData = UserSanitizer::sanitize($userData);
        $isValidate = UserValidator::validate($userData);
        if ($isValidate) {
            $userData["password"] = password_hash($userData["password"], PASSWORD_BCRYPT);

            $UserModel = new UserModel();
            $results = $UserModel->readUserByEmailPassword($userData["email"], $userData["password"]);

            if (!$results){
//SEGUIMOS PARA LOGUEO

                return true;
            }
        }

        return false;
    }

    public function deleteUser($POST)
    {
        $userData = [
            'emailAddress'          => $POST['emailAddress'] ?? "",
            'password'              => $POST['password'] ?? "",
            'dateTime'  => date('Y-m-d H:i:s')
        ];
        $userData = UserSanitizer::sanitize($userData);
        if(UserValidator::validate($userData)) {
            $UserModel = new UserModel();
            if ($UserModel->deleteUserByEmailAndPassword($userData["emailAddress"],$userData["password"])) {
                return true;
            }
        }
        return false;
    }
}
