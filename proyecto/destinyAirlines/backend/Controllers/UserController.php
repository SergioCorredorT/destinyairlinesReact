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
        //En $POST solo se deben recibir los campos que se desean editar dentro de los posibles definidos en $userData
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
            'emailAddressAuth'      => $POST['emailAddressAuth'] ?? null,
            'dateTime'  => date('Y-m-d H:i:s')
        ];

        require_once './Tools/TokenTool.php';
        $iniTool = new IniTool('./Config/cfg.ini');
        $tokenSettings = $iniTool->getKeysAndValues("tokenSettings");
        $secondsMinTimeLifeAccessToken = intval($tokenSettings["secondsMinTimeLifeAccessToken"]);
        $secondsMinTimeLifeRefreshToken = intval($tokenSettings["secondsMinTimeLifeRefreshToken"]);
        $secondsMaxTimeLifeAccessToken = intval($tokenSettings["secondsMaxTimeLifeAccessToken"]);
        $secondsMaxTimeLifeRefreshToken = intval($tokenSettings["secondsMaxTimeLifeRefreshToken"]);

        $decodedToken = null;

        $newTokens = TokenTool::checkUpdateByRemainingTokenTimes($userData["accessToken"], $userData["refreshToken"], $secondsMinTimeLifeAccessToken, $secondsMinTimeLifeRefreshToken, $secondsMaxTimeLifeAccessToken, $secondsMaxTimeLifeRefreshToken);
        if (isset($newTokens["accessToken"])) {
            $decodedToken = TokenTool::decodeAndCheckToken($newTokens["accessToken"], "access");
        } else {
            $decodedToken = TokenTool::decodeAndCheckToken($userData["accessToken"], "access");
        }

        if ($decodedToken["response"]) {
            $UserModel = new UserModel();
            $email = $UserModel->getEmailById($decodedToken["response"]->data->id);

            if ($email === $POST['emailAddressAuth']) {
                $userData = UserSanitizer::sanitize($userData);
                if (UserValidator::validate($userData)) {
                    if (isset($userData["password"])) {
                        $userData["passwordHash"] = "'" . password_hash($userData["password"], PASSWORD_BCRYPT) . "'";
                        unset($userData["password"]);
                    }

                    if ($UserModel->updateUsersByEmail(array_filter($userData), $email)) {
                        return ["response" => true, "tokens" => $newTokens];
                    }
                }
            }
        }
        return ["response" => false, "tokens" => $newTokens];
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
        $decodedToken = TokenTool::decodeAndCheckToken($userData["refreshToken"], "refresh");
        $UserModel = new UserModel();

        if ($decodedToken["response"]) {
            $email = $UserModel->getEmailById($decodedToken["response"]->data->id);

            if ($email === $POST['emailAddress']) {
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
            $results = $UserModel->readUserByEmail($userData["emailAddress"]);
            if ($results) {
                $iniTool = new IniTool('./Config/cfg.ini');
                $aboutLogin = $iniTool->getKeysAndValues("aboutLogin");
                $maxFailedLoginAttemps = intval($aboutLogin["maxFailedLoginAttemps"]);

                if (intval($results[0]["failedAttempts"]) < $maxFailedLoginAttemps - 1) {
                    if (password_verify($userData["password"], $results[0]["passwordHash"])) {
                        $UserModel->updateResetFailedAttempts($results[0]["id_USERS"]);
                        $tokenSettings = $iniTool->getKeysAndValues("tokenSettings");
                        $secondsMaxTimeLifeAccessToken = intval($tokenSettings["secondsMaxTimeLifeAccessToken"]);
                        $secondsMaxTimeLifeRefreshToken = intval($tokenSettings["secondsMaxTimeLifeRefreshToken"]);

                        //Poner a null el lastPasswordResetEmailSentAt si es que tenía algo
                        if ($results[0]['lastPasswordResetEmailSentAt']) {
                            $UserModel->updateLastPasswordResetEmailSentAt("NULL", $results[0]["id_USERS"]);
                        }

                        $data = [
                            "id" => $results[0]["id_USERS"],
                            "type" => "access"
                        ];
                        $accessToken = TokenTool::generateToken($data, $secondsMaxTimeLifeAccessToken);

                        $data = [
                            "id" => $results[0]["id_USERS"],
                            "type" => "refresh"
                        ];
                        $refreshToken = TokenTool::generateToken($data, $secondsMaxTimeLifeRefreshToken);
                        return ["tokens" => ["accessToken" => $accessToken, "refreshToken" => $refreshToken]];
                    } else {
                        $UserModel->updateAddFailedAttempts($results[0]["id_USERS"]);
                        [$user] = $UserModel->readUserById($results[0]["id_USERS"]);
                        return ["response" => false, "failedAttempts" => $user["failedAttempts"], "lastFailedAttempt" => $user["lastFailedAttempt"]];
                    }
                } else {
                    //Comprobamos si se envió el correo
                    $isEmailSent = false;
                    if (!$results[0]['lastPasswordResetEmailSentAt']) { //
                        $UserModel->updateAddFailedAttempts($results[0]["id_USERS"]); //
                        $userRestartData['toEmail'] = $results[0]['emailAddress'];

                        $originEmailIni = $iniTool->getKeysAndValues("originEmail");
                        $userRestartData['fromEmail'] = $originEmailIni['email'];
                        $userRestartData['fromPassword'] = $originEmailIni['password'];
                        $userRestartData['lastFailedAttempt'] = $results[0]["lastFailedAttempt"];
                        $userRestartData['subject'] = "Cambio de contraseña";

                        //Crear token
                        $tokenSettings = $iniTool->getKeysAndValues("tokenSettings");
                        $secondsMaxTimeLifeAccessToken = intval($tokenSettings["secondsMaxTimeLifeAccessToken"]);
                        $data = [
                            "id" => $results[0]["id_USERS"],
                            "type" => "unblock"
                        ];
                        $unblockToken = TokenTool::generateToken($data, $secondsMaxTimeLifeAccessToken);

                        $tempId=TokenTool::generateUUID();
                        require_once './Models/UserTempIdsModel.php';
                        $UserTempIdsModel = new UserTempIdsModel();
                        //insertar tempId en bbdd
                        $UserTempIdsModel->createTempId($userId, $tempId);
                        //insertar tempId en la url
                        //Crear link
                        $userRestartData["unblockLink"] = $aboutLogin["endpointResetPasswordLink"] . "?unblockToken=" . urlencode($unblockToken) . "&tempId=" . urlencode($tempId);

                        require_once './Tools/EmailTool.php';
                        $isEmailSent = EmailTool::sendEmail($userRestartData, "failedAttemptsTemplate");
                        if ($isEmailSent) {
                            //Si el email se ha enviado guardamos la fecha de envío
                            $UserModel->updateLastPasswordResetEmailSentAt(date('Y-m-d H:i:s'), $results[0]["id_USERS"]);
                        }
                    }
                    [$user] = $UserModel->readUserById($results[0]["id_USERS"]);
                    return ["response" => false, "failedAttempts" => $user["failedAttempts"], "emailSent" => $isEmailSent];
                }
            }
        }
        return false;
    }

    public function logoutUser($POST)
    {
        require_once './Tools/TokenTool.php';

        $userData = [
            'refreshToken'  => $POST['refreshToken'] ?? "",
            'dateTime'      => date('Y-m-d H:i:s')
        ];
        $decodedToken = TokenTool::decodeAndCheckToken($userData["refreshToken"], "refresh");
        if ($decodedToken["response"]) {
            //eliminar tokens en el frontend
            //Un método más seguro es hacer en server una lista blanca o negra para dejar pasar solo a los refresh tokens adecuados, pero requeriría carga en el server, y se desaprovecha la ventaja de no hacer un logueo basado en session
            require_once './Tools/SessionTool.php';
            SessionTool::destroy();
            return true;
        }
        return false;
    }

    public function passwordReset($POST)
    {
        $new_password = $POST['new_password'];
        $confirm_password = $POST['confirm_password'];
        $unblockToken = $POST['unblockToken'];

        if ($new_password == $confirm_password) {
            try {
                require_once './Tools/TokenTool.php';
                $dedodedUnblockToken = TokenTool::decodeAndCheckToken($unblockToken, "unblock");
                require_once './Models/UserModel.php';
                $UserModel = new UserModel();
                $UserTempIdsModel = new UserTempIdsModel();
                if ($dedodedUnblockToken["response"]) {
                    $userId = $dedodedUnblockToken["response"]->data->id;
                    //comprobar si el campo del email enviado es distinto de null
                    if (!is_null($UserModel->readLastPasswordResetEmailSentAt($userId)[0]["lastPasswordResetEmailSentAt"])) {
                        //Reiniciamos el contador de intentos
                        $UserModel->updateResetFailedAttempts($userId);

                        //Insertamos en bbdd la nueva contraseña hasheada
                        $UserModel->updatePasswordHashById(password_hash($new_password, PASSWORD_BCRYPT), $userId);

                        //modificar a null el campo del email enviado de la tabla users
                        $UserModel->updateLastPasswordResetEmailSentAt("NULL", $userId);

                        $UserTempIdsModel->deleteTempIdByUserId($userId);
                        return ["response" => true, "message" => '<span class="success">Contraseña cambiada con éxito, puede cerrar esta página.</span>'];
                    } else {
                        return ["response" => false, "message" => '<span class="warning">Contraseña ya fue cambiada en una anterior ocasión.</span>'];
                    }
                } else {
                    //El código de error 1 corresponde a token caducado
                    if ($dedodedUnblockToken["errorCode"] === 1) {
                        //------------------------------------------------
                        //En las 2 siguientes necesito acceder al id que contenía el token caducado o saber de algún modo el id del usuario

                        $tempId = $POST['tempId'];
                        $results = $UserTempIdsModel->readUserByTempId($tempId);
                        $results = $UserModel->readUserById( $results[0]["id_USERS"]);
                        $userId = $results[0]["id_USERS"];
                        $UserTempIdsModel->deleteTempIdByUserId($userId);

                        //poner a null el lastPasswordResetEmailSentAt de bbdd y haga algo (hacer post al login() o crear código aquí) para enviar nuevo token
                        $UserModel->updateLastPasswordResetEmailSentAt("NULL", $userId);

                        //Creamos datos para el token
                        $userRestartData['toEmail'] = $results[0]['emailAddress'];

                        $iniTool = new IniTool('./Config/cfg.ini');
                        $originEmailIni = $iniTool->getKeysAndValues("originEmail");
                        $userRestartData['fromEmail'] = $originEmailIni['email'];
                        $userRestartData['fromPassword'] = $originEmailIni['password'];
                        $userRestartData['lastFailedAttempt'] = $results[0]["lastFailedAttempt"];
                        $userRestartData['subject'] = "Cambio de contraseña";
                        //Generamos token
                        $tokenSettings = $iniTool->getKeysAndValues("tokenSettings");
                        $secondsMaxTimeLifeAccessToken = intval($tokenSettings["secondsMaxTimeLifeAccessToken"]);
                        $data = [
                            "id" => $results[0]["id_USERS"],
                            "type" => "unblock"
                        ];
                        $unblockToken = TokenTool::generateToken($data, $secondsMaxTimeLifeAccessToken);

                        $tempId=TokenTool::generateUUID();
                        require_once './Models/UserTempIdsModel.php';
                        $UserTempIdsModel = new UserTempIdsModel();
                        //insertar tempId en bbdd
                        $UserTempIdsModel->createTempId($userId, $tempId);

                        //Generamos link
                        $userRestartData["unblockLink"] = $aboutLogin["endpointResetPasswordLink"] . "?unblockToken=" . urlencode($unblockToken);

                        //enviamos email con link+token
                        require_once './Tools/EmailTool.php';
                        $isEmailSent = EmailTool::sendEmail($userRestartData, "failedAttemptsTemplate");
                        if ($isEmailSent) {
                            //Si el email se ha enviado guardamos la fecha de envío
                            $UserModel->updateLastPasswordResetEmailSentAt(date('Y-m-d H:i:s'), $results[0]["id_USERS"]);
                        }

                        //----------
                        return ["response" => false, "message" => '<span class="warning">Token caducado. Le hemos enviado un nuevo enlace de activación a su dirección de correo electrónico, por favor, no se demore mucho en acceder al enlace enviado para evitar su caducidad</span>'];
                    }
                }
            } catch (Exception $er) {
                error_log('Catched exception: ' .  $er->getMessage() . "\n");
                return ["response" => false, "message" => '<span class="error">Hubo un error en el cambio de contraseña.</span>'];
            }
        }
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
