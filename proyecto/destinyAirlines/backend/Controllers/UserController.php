<?php
/*
tras logueo y envío de token al frontend
 en el frontend entonces se creará un localstorage con ese token para enviarlo al server cuando sea necesario.
	Entonces cuando el usuario haga una acción en la que sea necesaria una comprobación de servidor, el frontend enviará al php el token, el cual comprobará con secretPass
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
        $cfgTokenSettings = $iniTool->getKeysAndValues("tokenSettings");
        $secondsMinTimeLifeAccessToken = intval($cfgTokenSettings["secondsMinTimeLifeAccessToken"]);
        $secondsMinTimeLifeRefreshToken = intval($cfgTokenSettings["secondsMinTimeLifeRefreshToken"]);
        $secondsMaxTimeLifeAccessToken = intval($cfgTokenSettings["secondsMaxTimeLifeAccessToken"]);
        $secondsMaxTimeLifeRefreshToken = intval($cfgTokenSettings["secondsMaxTimeLifeRefreshToken"]);

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
            $UserTempIdsModel = new UserTempIdsModel();
            $user = $UserModel->readUserByEmail($userData["emailAddress"]);
            if ($user) {
                //Recogemos datos que necesitaremos
                [$user] = $user;
                $iniTool = new IniTool('./Config/cfg.ini');
                $cfgAboutLogin = $iniTool->getKeysAndValues("aboutLogin");
                $maxLoginAttemps = intval($cfgAboutLogin["maxLoginAttemps"]);
                $cfgTokenSettings = $iniTool->getKeysAndValues("tokenSettings");
                $secondsMaxTimeLifeAccessToken = intval($cfgTokenSettings["secondsMaxTimeLifeAccessToken"]);
                $secondsMaxTimeLifeRefreshToken = intval($cfgTokenSettings["secondsMaxTimeLifeRefreshToken"]);

                //El valor inicial de intentos de login es 0, entonces cada vez que iniciemos un intento le daremos un + 1 al que ya había
                $user["currentLoginAttempts"] = intval($user["currentLoginAttempts"]) + 1;

                //En BBDD solo actualizamos si es necesario
                if (intval($user["currentLoginAttempts"]) <= $maxLoginAttemps) {
                    $UserModel->updateAddCurrentLoginAttempts($user["id_USERS"]);
                    $user["lastAttempt"] = date('Y-m-d H:i:s');
                }

                //Si estamos dentro del número de intentos permitidos comprobamos el pass
                if (intval($user["currentLoginAttempts"]) <= $maxLoginAttemps && password_verify($userData["password"], $user["passwordHash"])) {
                    $UserModel->updateResetCurrentLoginAttempts($user["id_USERS"]);

                    $accessToken = TokenTool::generateToken(["id" => $user["id_USERS"], "type" => "access"], $secondsMaxTimeLifeAccessToken);
                    $refreshToken = TokenTool::generateToken(["id" => $user["id_USERS"], "type" => "refresh"], $secondsMaxTimeLifeRefreshToken);

                    return ["tokens" => ["accessToken" => $accessToken, "refreshToken" => $refreshToken]];
                //Si hemos fallado contraseña o si estamos fuera del rango de intentos comprobamos si debemos mandar el email al usuario
                } elseif (intval($user["currentLoginAttempts"]) >= $maxLoginAttemps) {
                    $isEmailSent = false;
                    //Comprobamos que no haya email de desbloqueo pendiente para saber si debemos enviarlo por primera vez
                    if (!$UserTempIdsModel->readUserByUserId($user["id_USERS"])) {
                        $userRestartData['toEmail'] = $user['emailAddress'];

                        $cfgOriginEmailIni = $iniTool->getKeysAndValues("originEmail");
                        $userRestartData['fromEmail'] = $cfgOriginEmailIni['email'];
                        $userRestartData['fromPassword'] = $cfgOriginEmailIni['password'];
                        $userRestartData['lastAttempt'] = $user["lastAttempt"];
                        $userRestartData['subject'] = "Cambio de contraseña";

                        //Crear token
                        $unlockToken = TokenTool::generateToken(["id" => $user["id_USERS"], "type" => "unlock"], $secondsMaxTimeLifeAccessToken);

                        //Insertar una id temporal en bbdd para enviarlo integrado en un link al email del user para evitar usar la id original del usuario.
                        //Esta id temporal sirve por si el token del link que se enviará por email caduca sin usarse, y entonces poder identificar de nuevo 
                        //  la dirección email del usuario.
                        $tempId = TokenTool::generateUUID();

                        //Generamos link que se enviará por email
                        $userRestartData["unlockLink"] = $this->generateUnlockLink($cfgAboutLogin["endpointResetPasswordLink"], ["unlockToken" => $unlockToken, "tempId" => $tempId]);

                        require_once './Tools/EmailTool.php';
                        $isEmailSent = EmailTool::sendEmail($userRestartData, "failedAttemptsTemplate");
                        if ($isEmailSent) {
                            //Si el email se ha enviado creamos registro como que hay un email de reactivación pendiente
                            $this->updateCreateTempIdByUserId($user["id_USERS"], $tempId);
                        }
                    }
                    return ["response" => false, "currentLoginAttempts" => $user["currentLoginAttempts"], "lastAttempt" => $user["lastAttempt"], "emailSent" => $isEmailSent];
                }
                //Si no hemos sobrepasado el número de intentos máximo y hemos fallado contraseña
                return ["response" => false, "currentLoginAttempts" => $user["currentLoginAttempts"], "lastAttempt" => $user["lastAttempt"]];
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
            //Recuerda que debemos eliminar tokens en el frontend
            //Un método más seguro es hacer en server una lista blanca o negra para dejar pasar solo a los refresh tokens adecuados,
            //  pero requeriría carga en el server, y se desaprovecha la ventaja de rendimiento frente a un logueo basado en session
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
        $unlockToken = $POST['unlockToken'];
        $tempId = $POST['tempId'];

        if ($new_password == $confirm_password) {
            try {
                require_once './Tools/TokenTool.php';
                require_once './Tools/EmailTool.php';
                $dedodedUnlockToken = TokenTool::decodeAndCheckToken($unlockToken, "unlock");
                $UserModel = new UserModel();
                $UserTempIdsModel = new UserTempIdsModel();
                if ($dedodedUnlockToken["response"]) {
                    $userId = $dedodedUnlockToken["response"]->data->id;
                    //Comprobamos si no hay un email de desbloqueo pendiente
                    if ($UserTempIdsModel->readUserByUserId($userId)) {
                        //Eliminamos el registro que decía que había un email de desbloqueo pendiente
                        $UserTempIdsModel->deleteTempIdByUserId($userId);

                        //Reiniciamos el contador de intentos
                        $UserModel->updateResetCurrentLoginAttempts($userId);

                        //Incorporamos la nueva pass en bbdd
                        $UserModel->updatePasswordHashById(password_hash($new_password, PASSWORD_BCRYPT), $userId);

                        return ["response" => true, "message" => '<span class="success">Contraseña cambiada con éxito, puede cerrar esta página.</span>'];
                    } else {
                        return ["response" => false, "message" => '<span class="warning">Contraseña ya fue cambiada en una anterior ocasión.</span>'];
                    }
                } else {
                    //El código de error 1 corresponde a token caducado
                    //y comprobamos si aún no se ha resuelto el id temporal, ya que es posible que el usuario intente de nuevo cambiar el pass desde la página del link enviado al email
                    if ($dedodedUnlockToken["errorCode"] === 1 && $UserTempIdsModel->readUserByTempId($tempId)) {
                        $readUserByTempIdResults = $UserTempIdsModel->readUserByTempId($tempId);

                        [$user] = $UserModel->readUserById($readUserByTempIdResults[0]["id_USERS"]);
                        $userId = $user["id_USERS"];

                        //Eliminamos el registro que decía que había un email de desbloqueo pendiente para después renovarlos
                        $UserTempIdsModel->deleteTempIdByUserId($userId);

                        //Recopilamos datos para el futuro email
                        $iniTool = new IniTool('./Config/cfg.ini');
                        $cfgOriginEmailIni = $iniTool->getKeysAndValues("originEmail");
                        $userRestartData['fromEmail'] = $cfgOriginEmailIni['email'];
                        $userRestartData['fromPassword'] = $cfgOriginEmailIni['password'];
                        $userRestartData['toEmail'] = $user['emailAddress'];
                        $userRestartData['lastAttempt'] = $user["lastAttempt"];
                        $userRestartData['subject'] = "Cambio de contraseña";

                        //Generamos token
                        $cfgTokenSettings = $iniTool->getKeysAndValues("tokenSettings");
                        $secondsMaxTimeLifeAccessToken = intval($cfgTokenSettings["secondsMaxTimeLifeAccessToken"]);
                        $unlockToken = TokenTool::generateToken(["id" => $user["id_USERS"], "type" => "unlock"], $secondsMaxTimeLifeAccessToken);

                        //Generamos id temporal en bbdd para después poder averiguar el id original del user y así saber su email en la página destino del link que se enviará por email
                        $tempId = TokenTool::generateUUID();

                        //Generamos link con el token e id temporal para identificar el id del user si caduca el token
                        $cfgAboutLogin = $iniTool->getKeysAndValues("aboutLogin");
                        $userRestartData["unlockLink"] = $this->generateUnlockLink($cfgAboutLogin["endpointResetPasswordLink"], ["unlockToken" => $unlockToken, "tempId" => $tempId]);

                        //Enviamos email con link+token+id temporal
                        $isEmailSent = EmailTool::sendEmail($userRestartData, "failedAttemptsTemplate");
                        if ($isEmailSent) {
                            //Si el email se ha enviado creamos registro de email de reactivación pendiente
                            $UserTempIdsModel->createTempId($userId, $tempId);
                        }

                        return ["response" => false, "message" => '<span class="warning">Token caducado. Le hemos enviado un nuevo enlace de activación a su dirección de correo electrónico, por favor, no se demore mucho en acceder al enlace enviado para evitar su caducidad</span>'];
                    }
                }
                return ["response" => false, "message" => '<span class="warning">No se pudo actualizar la contraseña. Es posible que haya sido actualizada en una anterior ocasión</span>'];
            } catch (Exception $er) {
                error_log('Catched exception: ' .  $er->getMessage() . "\n");
                return ["response" => false, "message" => '<span class="error">Hubo un error en el cambio de contraseña.</span>'];
            }
        }
        return ["response" => false];
    }

    private function updateCreateTempIdByUserId($userId, $tempId)
    {
        $UserTempIdsModel = new UserTempIdsModel();
        $UserTempIdsModel->removeTempIdIfExistByIdUser($userId);
        $UserTempIdsModel->createTempId($userId, $tempId);
    }

    private function generateUnlockLink($base, $parametres)
    {
        return $base . $this->generateLinkParametres($parametres);
    }

    private function generateLinkParametres($array)
    {
        $linkParametres = "";
        foreach ($array as $key => $value) {
            $linkParametres .= urlencode($key) . "=" . urlencode($value) . "&";
        }
        return rtrim('?' . $linkParametres, '&'); // Eliminamos el último '&'
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
