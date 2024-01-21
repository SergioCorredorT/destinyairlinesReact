<?php
/*
tras logueo y envío de token al frontend
 en el frontend entonces se creará un localstorage con ese token para enviarlo al server cuando sea necesario.
	Entonces cuando el usuario haga una acción en la que sea necesaria una comprobación de servidor, el frontend enviará al php el token, el cual comprobará con secretPass
*/
require_once ROOT_PATH . '/Controllers/BaseController.php';
require_once ROOT_PATH . '/Sanitizers/UserSanitizer.php';
require_once ROOT_PATH . '/Validators/UserValidator.php';
require_once ROOT_PATH . '/Tools/IniTool.php';
final class UserController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function createUser(array $POST)
    {
        $keys_default = [
            'documentationType' => '',
            'documentCode' => '',
            'expirationDate' => '',
            'title' => null,
            'firstName' => '',
            'lastName' => '',
            'townCity' => '',
            'streetAddress' => '',
            'zipCode' => '',
            'country' => '',
            'emailAddress' => '',
            'password' => '',
            'phoneNumber1' => '',
            'phoneNumber2' => null,
            'phoneNumber3' => null,
            'companyName' => null,
            'companyTaxNumber' => null,
            'companyPhoneNumber' => null,
            'dateBirth' => ''
        ];

        foreach ($keys_default as $key => $defaultValue) {
            $userData[$key] = $POST[$key] ?? $defaultValue;
        }

        $userData = UserSanitizer::sanitize($userData);
        $isValidate = UserValidator::validate($userData);
        if ($isValidate) {
            $userData['passwordHash'] = password_hash($userData['password'], PASSWORD_BCRYPT);
            unset($userData['password']);

            $UserModel = new UserModel();
            if ($UserModel->createUser($userData)) {
                return true;
            }
        }
        return false;
    }

    public function updateUser(array $POST)
    {
        require_once ROOT_PATH . '/Tools/TokenTool.php';
        //En $POST solo se deben recibir los campos que se desea usar, ya sea para enviar a BBDD o hacer algo, al final, justo antes del update, se extraerán los campos no usados para el update
        $optional_keys_default = [
            'title' => null,
            'firstName' => '',
            'lastName' => '',
            'townCity' => '',
            'streetAddress' => '',
            'zipCode' => '',
            'country' => '',
            'password' => '',
            'phoneNumber1' => '',
            'phoneNumber2' => null,
            'phoneNumber3' => null,
            'companyName' => null,
            'companyTaxNumber' => null,
            'companyPhoneNumber' => null,
            'documentationType' => '',
            'documentCode' => '',
            'expirationDate' => '',
            'dateBirth' => ''
        ];

        $fixed_keys_default = [
            'accessToken' => '',
            'emailAddressAuth' => ''
        ];

        //Recogemos los campos opcionales, con valor opcional (null), o con valor a validar ('')
        foreach ($optional_keys_default as $key => $defaultValue) {
            if (isset($POST[$key])) {
                $userData[$key] = !empty($POST[$key]) ? $POST[$key] : $defaultValue; //Antes que poner un '' ponemos un null
            }
        }
        foreach ($fixed_keys_default as $key => $defaultValue) {
            $userData[$key] = $POST[$key] ?? $defaultValue;
        }
        
        //SANEAR y VALIDAR
        $userData = UserSanitizer::sanitize($userData);
        if (!UserValidator::validate($userData)) {
            return false;
        }

        $decodedToken = TokenTool::decodeAndCheckToken($userData['accessToken'], 'access');

        if (!$decodedToken['response']) {
            return false;
        }
        $UserModel = new UserModel();

        $email = $UserModel->getEmailById($decodedToken['response']->data->id);

        if (!$email) {
            return false;
        }

        if ($email !== $POST['emailAddressAuth']) {
            return false;
        }

        if (isset($userData['password'])) {
            $userData['passwordHash'] = password_hash($userData["password"], PASSWORD_BCRYPT);
            unset($userData['password']);
        }

        //Quitamos los datos que no se deben editar en BBDD antes del update
        unset($userData['emailAddressAuth']);
        unset($userData['accessToken']);
        if ($UserModel->updateUsersByEmail($userData, $email)) {
            return true;
        }
        return false;
    }

    public function deleteUser(array $POST)
    {
        //eliminar tokens en el frontend
        require_once ROOT_PATH . '/Tools/TokenTool.php';
        $keys_default = [
            'emailAddress' => '',
            'password' => '',
            'refreshToken' => ''
        ];
        foreach ($keys_default as $key => $defaultValue) {
            $userData[$key] = $POST[$key] ?? $defaultValue;
        }

        $userData = UserSanitizer::sanitize($userData);
        if (!UserValidator::validate($userData)) {
            return false;
        }

        $decodedToken = TokenTool::decodeAndCheckToken($userData['refreshToken'], 'refresh');


        if (!$decodedToken['response']) {
            return false;
        }
        $UserModel = new UserModel();
        $email = $UserModel->getEmailById($decodedToken['response']->data->id);

        if ($email !== $POST['emailAddress']) {
            return false;
        }
        require_once ROOT_PATH . '/Tools/SessionTool.php';
        SessionTool::destroy();
        if ($UserModel->deleteUserByEmailAndPassword($userData['emailAddress'], $userData['password'])) {
            return true;
        }
        return false;
    }

    public function loginUser(array $POST)
    {
        require_once ROOT_PATH . '/Tools/TokenTool.php';
        $keys_default = [
            'emailAddress' => '',
            'password' => ''
        ];
        foreach ($keys_default as $key => $defaultValue) {
            $userData[$key] = $POST[$key] ?? $defaultValue;
        }

        $userData = UserSanitizer::sanitize($userData);
        $isValidate = UserValidator::validate($userData);

        if ($isValidate) {
            $UserModel = new UserModel();
            $UserTempIdsModel = new UserTempIdsModel();
            $user = $UserModel->readUserByEmail($userData['emailAddress']);

            if ($user) {
                //Recogemos datos que necesitaremos
                //[$user] = $user;
                $iniTool = new IniTool(ROOT_PATH  . '/Config/cfg.ini');
                $cfgAboutLogin = $iniTool->getKeysAndValues('aboutLogin');
                $maxLoginAttemps = intval($cfgAboutLogin['maxLoginAttemps']);
                $cfgTokenSettings = $iniTool->getKeysAndValues('tokenSettings');
                $secondsMaxTimeLifeAccessToken = intval($cfgTokenSettings['secondsMaxTimeLifeAccessToken']);
                $secondsMaxTimeLifeRefreshToken = intval($cfgTokenSettings['secondsMaxTimeLifeRefreshToken']);

                //El valor inicial de intentos de login es 0, entonces cada vez que iniciemos un intento le daremos un + 1 al que ya había
                $user['currentLoginAttempts'] = intval($user['currentLoginAttempts']) + 1;

                //En BBDD solo actualizamos si es necesario
                if (intval($user['currentLoginAttempts']) <= $maxLoginAttemps) {
                    $UserModel->updateAddCurrentLoginAttempts($user['id_USERS']);
                    $user['lastAttempt'] = date('Y-m-d H:i:s');
                }

                //Si estamos dentro del número de intentos permitidos comprobamos el pass
                if (intval($user['currentLoginAttempts']) <= $maxLoginAttemps && password_verify($userData['password'], $user['passwordHash'])) {
                    $UserModel->updateResetCurrentLoginAttempts($user['id_USERS']);

                    $accessToken = TokenTool::generateToken(['id' => $user['id_USERS'], 'type' => 'access'], $secondsMaxTimeLifeAccessToken);
                    $refreshToken = TokenTool::generateToken(['id' => $user['id_USERS'], 'type' => 'refresh'], $secondsMaxTimeLifeRefreshToken);

                    return ['tokens' => ['accessToken' => $accessToken, 'refreshToken' => $refreshToken], 'response' => ['userData' =>  ['title' => $user['title'] ,'firstName' => $user['firstName'], 'lastName' => $user['lastName']]]];
                    //Si hemos fallado contraseña o si estamos fuera del rango de intentos comprobamos si debemos mandar el email al usuario
                } elseif (intval($user['currentLoginAttempts']) >= $maxLoginAttemps) {
                    $isEmailSent = false;
                    //Comprobamos que no haya email de desbloqueo pendiente para saber si debemos enviarlo por primera vez
                    if (!$UserTempIdsModel->readUserByUserId($user['id_USERS'])) {
                        $cfgOriginEmailIni = $iniTool->getKeysAndValues('originEmail');
                        $userRestartData = [
                            'fromEmail' => $cfgOriginEmailIni['email'],
                            'fromPassword' => $cfgOriginEmailIni['password'],
                            'toEmail' => $user['emailAddress'],
                            'lastAttempt' => $user['lastAttempt'],
                            'subject' => 'Cambio de contraseña'
                        ];

                        //Generamos token
                        $failedAttemptsToken = TokenTool::generateToken(['id' => $user['id_USERS'], 'type' => 'failedAttempts'], $secondsMaxTimeLifeAccessToken);

                        //Insertar una id temporal en bbdd para enviarlo integrado en un link al email del user para evitar usar la id original del usuario.
                        //Esta id temporal sirve por si el token del link que se enviará por email caduca sin usarse, y entonces poder identificar de nuevo 
                        //  la dirección email del usuario.
                        $tempId = TokenTool::generateUUID();

                        //Generamos link que se enviará por email
                        $userRestartData['passwordResetLink'] = $this->generateLinkPasswordReset(
                            $cfgAboutLogin['mainControllerLink'], 
                            [
                                'passwordResetToken' => $failedAttemptsToken, 
                                'tempId' => $tempId, 
                                'type' => 'failedAttempts', 
                                'command' => 'goToPasswordReset'
                            ]);
                        require_once ROOT_PATH . '/Tools/EmailTool.php';
                        $isEmailSent = EmailTool::sendEmail($userRestartData, 'failedAttemptsTemplate');
                        if ($isEmailSent) {
                            //Si el email se ha enviado creamos registro como que hay un email de reactivación pendiente
                            $this->updateCreateTempIdByUserId($user['id_USERS'], $tempId);
                        }
                    }
                    return ['response' => false, 'currentLoginAttempts' => $user['currentLoginAttempts'], 'lastAttempt' => $user['lastAttempt'], 'emailSent' => $isEmailSent];
                }
                //Si no hemos sobrepasado el número de intentos máximo y hemos fallado contraseña
                return ['response' => false, 'currentLoginAttempts' => $user['currentLoginAttempts'], 'lastAttempt' => $user['lastAttempt']];
            }
        }
        return false;
    }

    public function logoutUser(array $POST)
    {
        require_once ROOT_PATH . '/Tools/TokenTool.php';

        $userData = [
            'refreshToken'  => $POST['refreshToken'] ?? ''
        ];

        $userData = UserSanitizer::sanitize($userData);
        if (!UserValidator::validate($userData)) {
            return false;
        }

        $decodedToken = TokenTool::decodeAndCheckToken($userData['refreshToken'], 'refresh');
        if ($decodedToken['response']) {
            //Recuerda que debemos eliminar tokens en el frontend
            //Un método más seguro es hacer en server una lista blanca o negra para dejar pasar solo a los refresh tokens adecuados,
            //  pero requeriría carga en el server, y se desaprovecha la ventaja de rendimiento frente a un logueo basado en session
            require_once ROOT_PATH . '/Tools/SessionTool.php';
            if (SessionTool::destroy()) {
                return true;
            }
        }
        return false;
    }

    public function passwordReset(array $POST)
    {
        $keys_default = [
            'type' => '',
            'new_password' => '',
            'confirm_password' => '',
            'passwordResetToken' => '',
            'tempId' => ''
        ];
        foreach ($keys_default as $key => $defaultValue) {
            $userData[$key] = $POST[$key] ?? $defaultValue;
        }

        require_once ROOT_PATH . '/Sanitizers/PasswordResetSanitizer.php';
        $userData = PasswordResetSanitizer::sanitize($userData);

        if ($POST['type']) {
            switch ($POST['type']) {
                case "failedAttempts": {
                        return $this->passwordResetFailedAttempts($userData);
                    }
                case "forgotPassword": {
                        return $this->passwordResetForgotPassword($userData);
                    }
            }
        }
    }

    public function passwordResetFailedAttempts(array $POST)
    {
        $new_password = $POST['new_password'];
        $confirm_password = $POST['confirm_password'];
        $passwordResetToken = $POST['passwordResetToken'];
        $tempId = $POST['tempId'];

        if ($new_password == $confirm_password) {
            try {
                require_once ROOT_PATH . '/Tools/TokenTool.php';
                require_once ROOT_PATH . '/Tools/EmailTool.php';
                $dedodedPasswordResetToken = TokenTool::decodeAndCheckToken($passwordResetToken, 'failedAttempts');
                $UserModel = new UserModel();
                $UserTempIdsModel = new UserTempIdsModel();
                if ($dedodedPasswordResetToken['response']) {
                    $userId = $dedodedPasswordResetToken['response']->data->id;
                    //Comprobamos si no hay un email de desbloqueo pendiente
                    if ($UserTempIdsModel->readUserByUserId($userId)) {
                        //Eliminamos el registro que decía que había un email de desbloqueo pendiente
                        $UserTempIdsModel->deleteTempIdByUserId($userId);

                        //Reiniciamos el contador de intentos
                        $UserModel->updateResetCurrentLoginAttempts($userId);

                        //Incorporamos la nueva pass en bbdd
                        $UserModel->updatePasswordHashById(password_hash($new_password, PASSWORD_BCRYPT), $userId);

                        return ['response' => true, 'message' => '<span class="success">Contraseña cambiada con éxito, puede cerrar esta página.</span>'];
                    } else {
                        return ['response' => false, 'message' => '<span class="warning">Contraseña ya fue cambiada en una anterior ocasión.</span>'];
                    }
                } else {
                    //y comprobamos si aún no se ha resuelto el id temporal, ya que es posible que el usuario intente de nuevo cambiar el pass desde la página del link enviado al email
                    $readUserByTempIdResults = $UserTempIdsModel->readUserByTempId($tempId);
                    if ($dedodedPasswordResetToken['errorName'] === 'expired_token' &&  $readUserByTempIdResults) {
                        $user = $UserModel->readUserById($readUserByTempIdResults[0]['id_USERS']);
                        $userId = $user['id_USERS'];

                        //Eliminamos el registro que decía que había un email de desbloqueo pendiente para después renovarlos
                        $UserTempIdsModel->deleteTempIdByUserId($userId);

                        //Recopilamos cfg
                        $iniTool = new IniTool(ROOT_PATH  . '/Config/cfg.ini');
                        $cfgOriginEmailIni = $iniTool->getKeysAndValues('originEmail');
                        $cfgTokenSettings = $iniTool->getKeysAndValues('tokenSettings');
                        $cfgAboutLogin = $iniTool->getKeysAndValues('aboutLogin');

                        //Recopilamos datos para el futuro email
                        $tempId = TokenTool::generateUUID();
                        $userRestartData = [
                            'fromEmail' => $cfgOriginEmailIni['email'],
                            'fromPassword' => $cfgOriginEmailIni['password'],
                            'toEmail' => $user['emailAddress'],
                            'lastAttempt' => $user['lastAttempt'],
                            'subject' => 'Cambio de contraseña',
                            'passwordResetLink' => $this->generateLinkPasswordReset($cfgAboutLogin['mainControllerLink'], [
                                'passwordResetToken' => TokenTool::generateToken(['id' => $user['id_USERS'], 'type' => 'failedAttempts'], intval($cfgTokenSettings['secondsMaxTimeLifeAccessToken'])),
                                'tempId' => $tempId,
                                'type' => 'failedAttempts',
                                'command' => 'goToPasswordReset'
                            ])
                        ];

                        //Enviamos email con link+token+id temporal
                        if (EmailTool::sendEmail($userRestartData, 'failedAttemptsTemplate')) {
                            //Si el email se ha enviado creamos registro de email de reactivación pendiente
                            $UserTempIdsModel->createTempId($userId, $tempId);
                        }

                        return ['response' => false, 'message' => '<span class="warning">Token caducado. Le hemos enviado un nuevo enlace de activación a su dirección de correo electrónico, por favor, no se demore mucho en acceder al enlace enviado para evitar su caducidad</span>'];
                    }
                }
                return ['response' => false, 'message' => '<span class="warning">No se pudo actualizar la contraseña. Es posible que el token haya caducado o que la contraseña ya se haya actualizado en una anterior ocasión.</span>'];
            } catch (Exception $er) {
                error_log('Catched exception: ' .  $er->getMessage() . "\n");
                return ['response' => false, 'message' => '<span class="error">Hubo un error en el cambio de contraseña.</span>'];
            }
        }
        return ['response' => false];
    }

    public function passwordResetForgotPassword(array $POST)
    {
        $new_password = $POST['new_password'];
        $confirm_password = $POST['confirm_password'];
        $passwordResetToken = $POST['passwordResetToken'];

        if ($new_password == $confirm_password) {
            try {
                require_once ROOT_PATH . '/Tools/TokenTool.php';
                $dedodedPasswordResetToken = TokenTool::decodeAndCheckToken($passwordResetToken, 'forgotPassword');
                if ($dedodedPasswordResetToken['response']) {
                    $userId = $dedodedPasswordResetToken['response']->data->id;
                    //Incorporamos la nueva pass en bbdd
                    $UserModel = new UserModel();
                    $user = $UserModel->readUserById($userId);
                    $response = false;

                    //Comprobamos que la cuenta no esté bloqueada por exceder el máximo de intentos permitidos de login
                    if ($user || intval($user['currentLoginAttempts']) < intval($cfgAboutLogin['maxLoginAttemps'])) {
                        $response = $UserModel->updatePasswordHashById(password_hash($new_password, PASSWORD_BCRYPT), $userId);
                    }

                    if ($response) {
                        return ['response' => $response, 'message' => '<span class="success">Contraseña cambiada con éxito, puede cerrar esta página.</span>'];
                    } else {
                        return ['response' => $response, 'message' => '<span class="error">No se pudo actualizar la contraseña.</span>'];
                    }
                } else {
                    return ['response' => false, 'message' => '<span class="warning">No se pudo actualizar la contraseña. Es posible que el token haya caducado. Por favor, inicie de nuevo el proceso cambio de contraseña si lo desea.</span>'];
                }
            } catch (Exception $er) {
                error_log('Catched exception: ' .  $er->getMessage() . "\n");
                return ['response' => false, 'message' => '<span class="error">Hubo un error en el cambio de contraseña.</span>'];
            }
        }
        return ['response' => false];
    }

    public function forgotPassword(array $POST)
    {
        $userData = [
            'emailAddress'  => $POST['emailAddress'] ?? ''
        ];

        $userData = UserSanitizer::sanitize($userData);
        $isValidate = UserValidator::validate($userData);
        if (!$isValidate) {
            return ['response' => false, 'errorCode' => 1];
        }

        //Recopilamos cfg
        $iniTool = new IniTool(ROOT_PATH  . '/Config/cfg.ini');
        $cfgOriginEmailIni = $iniTool->getKeysAndValues('originEmail');
        $cfgTokenSettings = $iniTool->getKeysAndValues('tokenSettings');
        $cfgAboutLogin = $iniTool->getKeysAndValues('aboutLogin');

        $UserModel = new UserModel();
        $user = $UserModel->readUserByEmail($userData['emailAddress']);

        if (!$user) {
            return ['response' => false, 'errorCode' => 2];
        }

        //Comprobamos que la cuenta no esté bloqueada por exceder el máximo de intentos permitidos de login
        if (intval($user['currentLoginAttempts']) >= intval($cfgAboutLogin['maxLoginAttemps'])) {
            return ['response' => false, 'errorCode' => 3];
        }

        //Comprobamos si desde la última petición de forgot password ha pasado menos del tiempo de caducidad del token forgotPassword (estipulado en cfg.ini)
        if (!is_null($user['lastForgotPasswordEmail'])) {
            $now = new DateTime();
            $lastForgotPasswordEmail = new DateTime($user['lastForgotPasswordEmail']);
            $interval = $now->getTimestamp() - $lastForgotPasswordEmail->getTimestamp();

            $expireTimeTokenForgotPassword = intval($cfgTokenSettings['secondsMinTimeLifeForgotPasswordToken']);

            if ($interval < $expireTimeTokenForgotPassword) {
                return ['response' => false, 'errorCode' => 4];
            }
        }

        require_once ROOT_PATH . '/Tools/TokenTool.php';
        $userRestartData = [
            'fromEmail' => $cfgOriginEmailIni['email'],
            'fromPassword' => $cfgOriginEmailIni['password'],
            'toEmail' => $user['emailAddress'],
            'subject' => 'Cambio de contraseña por olvido de contraseña original',
            'forgotPasswordLink' => $this->generateLinkPasswordReset(
                $cfgAboutLogin['mainControllerLink'],
                [
                    'passwordResetToken' => TokenTool::generateToken(['id' => $user['id_USERS'], 'type' => 'forgotPassword'], intval($cfgTokenSettings['secondsMinTimeLifeForgotPasswordToken'])),
                    'type' => 'forgotPassword',
                    'command' => 'goToPasswordReset'
                ]
            )
        ];

        require_once ROOT_PATH . '/Tools/EmailTool.php';

        $isSent = EmailTool::sendEmail($userRestartData, 'forgotPasswordTemplate');
        if ($isSent) {
            if ($UserModel->updateLastForgotPasswordEmailById($user['id_USERS'])) {
                return ['response' => true];
            }
        }
        return ['response' => false, 'errorCode' => 5];
    }

    private function updateCreateTempIdByUserId(int $userId, string $tempId)
    {
        $UserTempIdsModel = new UserTempIdsModel();
        $UserTempIdsModel->removeTempIdIfExistByIdUser($userId);
        $UserTempIdsModel->createTempId($userId, $tempId);
    }

    private function generateLinkPasswordReset(string $base, array $parametres)
    {
        return $base . $this->generateLinkParametres($parametres);
    }

    private function generateLinkParametres(array $parametres)
    {
        $linkParametres = '';
        foreach ($parametres as $key => $value) {
            $linkParametres .= urlencode($key) . '=' . urlencode($value) . '&';
        }
        return rtrim('?' . $linkParametres, '&'); // Eliminamos el último '&'
    }

    /*
    private function generateTokenSession($user)
    {//OLD, la autenticación basada en sesiones es otro modo (sobrecarga el server y no sirve para varias API a la vez porque es necesaria una session en cada una, aunque tiene otros modos seguros),
        // para este proyecto prefiero la autenticación basada en tokens
        $token = hash('sha256', uniqid(mt_rand(), true));
        SessionTool::startSession('destinyAirlines_session', 60 * 60 * 10);
        $_SESSION['login'] = ['token' => $token, 'id' => $user['id_USERS']];
        return $token;
    }
*/
}
