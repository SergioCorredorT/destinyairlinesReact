<?php
/*
tras logueo y envío de token al frontend
 en el frontend entonces se creará un localstorage con ese token para enviarlo al server cuando sea necesario.
	Entonces cuando el usuario haga una acción en la que sea necesaria una comprobación de servidor, el frontend enviará al php el token, el cual comprobará con secretPass
*/
require_once ROOT_PATH . '/Controllers/BaseController.php';
final class UserController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        parent::loadFilter('User');
    }

    public function getUserEditableInfo(array $POST) {
        $userData = $this->filter->filterGetUserEditableInfoData($POST);
        $userData = $this->processData->processData($userData, 'User');
        if (!$userData) {
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

        if ($email !== $userData['emailAddress']) {
            return false;
        }

        $userInfo = $UserModel->readUserEditableInfoByEmail($userData['emailAddress']);
        if (!$userInfo) {
            return  ['response' => false, 'message' => 'Hubo un error en la obtención de datos del usuario.'];
        }
        return  ['response' => $userInfo, 'message' => 'Obtenidos datos de usuario.'];
    }

    public function createUser(array $POST)
    {
        $userData = $this->filter->filterCreateUserData($POST);
        $secretKeys = $this->iniTool->getKeysAndValues('secretKeys');
        
       // require_once ROOT_PATH . '/DataProcessing/Validators/TokenValidator.php';

        $captchaToken = $this->processData->processData(['captchaToken'=>['token'=>$userData['captchaToken'],'secretCaptchaKey'=>$secretKeys['captchaSecretKey']]], 'Token');
        if (!$captchaToken) {
            return ['response'=> false,'message'=> 'Validación de captcha incorrecta'];
        }

        unset($userData['captchaToken']);

        $userData = $this->processData->processData($userData, 'User');
        if (!$userData) {
            return  ['response' => false, 'message' => 'No se pudo crear la cuenta. El formato de los datos recibidos es inválido.'];
        }

        $userData['passwordHash'] = password_hash($userData['password'], PASSWORD_BCRYPT);
        unset($userData['password']);

        $UserModel = new UserModel();
        $userId = $UserModel->createUser($userData, true);
        if (!$userId) {
            return  ['response' => false, 'message' => 'No se ha podido crear la cuenta. Esto puede deberse a un error del servidor o a que ya hay una cuenta registrada con ese email o a que ya se haya enviado un correo electrónico de activación de cuenta. Por favor, revisa tu correo electrónico para obtener instrucciones detalladas. Si no reconoces la solicitud de creación de la cuenta, por favor, haz clic en el enlace de revocación de la cuenta que se proporciona en el correo electrónico.'];
        }

        $userId = $userId[0];
        //Recopilamos cfg
        $cfgTokenSettings = $this->iniTool->getKeysAndValues('tokenSettings');
        $secondsTimeLifeActivationAccount = intval($cfgTokenSettings['secondsTimeLifeActivationAccount']);
        $cfgOriginEmailIni = $this->iniTool->getKeysAndValues('originEmail');
        $cfgAboutLogin = $this->iniTool->getKeysAndValues('aboutLogin');

        $tempId = TokenTool::generateUUID();

        $userVerificationData = [
            'fromEmail' => $cfgOriginEmailIni['email'],
            'fromPassword' => $cfgOriginEmailIni['password'],
            'toEmail' => $userData['emailAddress'],
            'subject' => 'Activación de cuenta registrada recientemente',
            'emailVerificationLink' => $this->generateLink($cfgAboutLogin['mainControllerLink'], [
                'emailVerificationToken' => TokenTool::generateToken(['id' => $userId, 'type' => 'emailVerification'], $secondsTimeLifeActivationAccount),
                'tempId' => $tempId,
                'type' => 'emailVerification',
                'command' => 'goToEmailVerification'
            ]),
            'accountDeletionLink' => $this->generateLink($cfgAboutLogin['mainControllerLink'], [
                'accountDeletionToken' => TokenTool::generateToken(['id' => $userId, 'userId' => $userId, 'type' => 'accountDeletion'], $secondsTimeLifeActivationAccount),
                'tempId' => $tempId,
                'type' => 'accountDeletion',
                'command' => 'goToAccountDeletion'
            ])
        ];
        if (EmailTool::sendEmail($userVerificationData, 'emailVerificationTemplate')) {
            //Si el email se ha enviado creamos registro de email de reactivación pendiente
            $UserTempIdsModel = new UserTempIdsModel();
            $UserTempIdsModel->createTempId($userId, $tempId, 'emailVerification');
            return  ['response' => true, 'message' => 'Email de activación de cuenta enviado con éxito. Por favor, revise su email y siga sus instrucciones para posibilitar que se autentique con su cuenta.'];
        }

        //Limpiar el usuario y el tempId creados por no ser posible su activación mediante email, ya que no se envió email (el tempId se elimina desde el on delete cascade)
        $UserModel->deleteUserById($userId);
        return  ['response' => false, 'message' => 'Hubo un error en el envío de email de activación de cuenta. Por favor, vuelva a intentar crear la cuenta.'];
    }

    public function updateUser(array $POST)
    {
        //En $POST solo se deben recibir los campos que se desea usar, ya sea para enviar a BBDD o hacer algo, al final, justo antes del update, se extraerán los campos no usados para el update
        $userData = $this->filter->filterUpdateUserData($POST);

        //SANEAR y VALIDAR
        $userData = $this->processData->processData($userData, 'User');
        if (!$userData) {
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

        //Quitamos los datos que no se deben editar en BBDD antes del update
        unset($userData['emailAddressAuth']);
        unset($userData['accessToken']);
        if ($UserModel->updateUsersByEmail($userData, $email)) {
            return true;
        }
        return false;
    }

    public function updatePassword(array $POST)
    {
        $userData = $this->filter->filterUpdatePasswordData($POST);

        //SANEAR y VALIDAR
        $userData = $this->processData->processData($userData, 'User');
        if (!$userData) {
            return false;
        }

        $decodedToken = TokenTool::decodeAndCheckToken($userData['accessToken'], 'access');

        if (!$decodedToken['response']) {
            return false;
        }
        $UserModel = new UserModel();

        $user = $UserModel->readUserById($decodedToken['response']->data->id);
        $email = $user['emailAddress'];

        if (!$email) {
            return false;
        }

        if ($email !== $userData['emailAddress']) {
            return false;
        }

        if (!password_verify($userData['oldPassword'], $user['passwordHash'])) {
            return false;
        }

        $userData['passwordHash'] = password_hash($userData['password'], PASSWORD_BCRYPT);

        unset($userData['password']);
        unset($userData['oldPassword']);
        //Quitamos los datos que no se deben editar en BBDD antes del update
        unset($userData['emailAddress']);
        unset($userData['accessToken']);
        if (!$UserModel->updateUsersByEmail($userData, $email)) {
            return false;
        }

        $cfgOriginEmailIni = $this->iniTool->getKeysAndValues('originEmail');
        $userChangePasswordData = [
            'fromEmail' => $cfgOriginEmailIni['email'],
            'fromPassword' => $cfgOriginEmailIni['password'],
            'toEmail' =>$email,
            'subject' => 'Cambio de contraseña'
        ];
        return EmailTool::sendEmail($userChangePasswordData, 'changePasswordTemplate');
    }

    public function deleteUser(array $POST)
    {
        //eliminar tokens en el frontend
        $userData = $this->filter->filterDeleteUserData($POST);
        $userData = $this->processData->processData($userData, 'User');
        if (!$userData) {
            return false;
        }

        $decodedToken = TokenTool::decodeAndCheckToken($userData['refreshToken'], 'refresh');

        if (!$decodedToken['response']) {
            return false;
        }
        $UserModel = new UserModel();
        $email = $UserModel->getEmailById($decodedToken['response']->data->id);

        if ($email !== $userData['emailAddress']) {
            return false;
        }
        SessionTool::destroy();
        if (!$UserModel->deleteUserByEmailAndPassword($userData['emailAddress'], $userData['password'])) {
            return false;
        }

        //Enviar email
        $cfgOriginEmailIni = $this->iniTool->getKeysAndValues('originEmail');
        $accountDeletionData = [
            'fromEmail' => $cfgOriginEmailIni['email'],
            'fromPassword' => $cfgOriginEmailIni['password'],
            'toEmail' => $email,
            'subject' => 'Eliminación de cuenta'
        ];

        if (!EmailTool::sendEmail($accountDeletionData, 'accountDeletionTemplate')) {
            return false;
        }

        return true;
    }

    public function loginUser(array $POST)
    {
        $userData = $this->filter->filterLoginUserData($POST);
        $userData = $this->processData->processData($userData, 'User');
        if (!$userData) {
            return false;
        }

        $UserModel = new UserModel();
        $UserTempIdsModel = new UserTempIdsModel();
        $user = $UserModel->readUserVerifiedByEmail($userData['emailAddress']);

        if (!$user) {
            return ['response' => false];
        }
        //Recogemos datos que necesitaremos
        //[$user] = $user;
        $cfgAboutLogin = $this->iniTool->getKeysAndValues('aboutLogin');
        $maxLoginAttemps = intval($cfgAboutLogin['maxLoginAttemps']);
        $cfgTokenSettings = $this->iniTool->getKeysAndValues('tokenSettings');
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

            //return ['tokens' => ['accessToken' => $accessToken, 'refreshToken' => $refreshToken], 'response' => ['userData' =>  ['title' => $user['title'], 'firstName' => $user['firstName'], 'lastName' => $user['lastName']]]];
            return [
                'tokens' => [
                    'accessToken' => $accessToken,
                    'refreshToken' => $refreshToken
                ],
                'response' => [
                    'userData' =>  [
                    'title' => $user['title'],
                    'firstName' => $user['firstName'],
                    'lastName' => $user['lastName'],
                    'country' => $user['country'],
                    'townCity' => $user['townCity'],
                    'streetAddress' => $user['streetAddress'],
                    'zipCode' => $user['zipCode'],
                    'phoneNumber1' => $user['phoneNumber1'],
                    'phoneNumber2' => $user['phoneNumber2'],
                    'phoneNumber3' => $user['phoneNumber3'],
                    'companyName' => $user['companyName'],
                    'companyTaxNumber' => $user['companyTaxNumber'],
                    'companyPhoneNumber' => $user['companyPhoneNumber'],
                    'documentationType' => $user['documentationType'],
                    'documentCode' => $user['documentCode'],
                    'expirationDate' => $user['expirationDate'],
                    'dateBirth' => $user['dateBirth']
                    ]
                ]
                ];
                
            //Si hemos fallado contraseña o si estamos fuera del rango de intentos comprobamos si debemos mandar el email al usuario
        } elseif (intval($user['currentLoginAttempts']) >= $maxLoginAttemps) {
            $isEmailSent = false;
            //Comprobamos que no haya email de desbloqueo pendiente para saber si debemos enviarlo por primera vez
            if (!$UserTempIdsModel->readUserByUserId($user['id_USERS'], 'failedAttemptsTemplate')) {
                $cfgOriginEmailIni = $this->iniTool->getKeysAndValues('originEmail');
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
                $userRestartData['passwordResetLink'] = $this->generateLink(
                    $cfgAboutLogin['mainControllerLink'],
                    [
                        'passwordResetToken' => $failedAttemptsToken,
                        'tempId' => $tempId,
                        'type' => 'failedAttempts',
                        'command' => 'goToPasswordReset'
                    ]
                );
                $isEmailSent = EmailTool::sendEmail($userRestartData, 'failedAttemptsTemplate');
                if ($isEmailSent) {
                    //Si el email se ha enviado creamos registro como que hay un email de reactivación pendiente
                    $this->updateCreateTempIdByUserId($user['id_USERS'], $tempId, 'failedAttempts');
                }
            }
            return ['response' => false, 'currentLoginAttempts' => $user['currentLoginAttempts'], 'lastAttempt' => $user['lastAttempt'], 'emailSent' => $isEmailSent];
        }
        //Si no hemos sobrepasado el número de intentos máximo y hemos fallado contraseña
        return ['response' => false, 'currentLoginAttempts' => $user['currentLoginAttempts'], 'lastAttempt' => $user['lastAttempt']];
    }

    public function logoutUser(array $POST)
    {
        $userData = [
            'refreshToken'  => $POST['refreshToken'] ?? ''
        ];

        $userData = $this->processData->processData($userData, 'User');
        if (!$userData) {
            return false;
        }

        $decodedToken = TokenTool::decodeAndCheckToken($userData['refreshToken'], 'refresh');
        if ($decodedToken['response']) {
            //Recuerda que debemos eliminar tokens en el frontend
            //Un método más seguro es hacer en server una lista blanca o negra para dejar pasar solo a los refresh tokens adecuados,
            //  pero requeriría carga en el server, y se desaprovecha la ventaja de rendimiento frente a un logueo basado en session
            if (SessionTool::destroy()) {
                return true;
            }
        }
        return false;
    }

    public function passwordReset(array $POST)
    {
        $userData = $this->filter->filterPasswordResetData($POST);
        $userData = $this->processData->processData($userData, 'PasswordReset');

        if ($POST['type']) {
            switch ($POST['type']) {
                case 'failedAttempts': {
                        return $this->passwordResetFailedAttempts($userData);
                    }
                case 'forgotPassword': {
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
                $dedodedPasswordResetToken = TokenTool::decodeAndCheckToken($passwordResetToken, 'failedAttempts');
                $UserModel = new UserModel();
                $UserTempIdsModel = new UserTempIdsModel();
                if ($dedodedPasswordResetToken['response']) {
                    $userId = $dedodedPasswordResetToken['response']->data->id;
                    //Comprobamos si no hay un email de desbloqueo pendiente
                    if ($UserTempIdsModel->readUserByUserId($userId, 'failedAttempts')) {
                        //Eliminamos el registro que decía que había un email de desbloqueo pendiente
                        $UserTempIdsModel->deleteTempIdByUserId($userId, 'failedAttempts');

                        //Reiniciamos el contador de intentos
                        $UserModel->updateResetCurrentLoginAttempts($userId);

                        //Incorporamos la nueva pass en bbdd
                        $UserModel->updatePasswordHashById("'" . password_hash($new_password, PASSWORD_BCRYPT) . "'", $userId);

                        return ['response' => true, 'message' => '<span class="success">Contraseña cambiada con éxito, puede cerrar esta página.</span>'];
                    } else {
                        return ['response' => false, 'message' => '<span class="warning">Contraseña ya fue cambiada en una anterior ocasión.</span>'];
                    }
                } else {
                    //y comprobamos si aún no se ha resuelto el id temporal, ya que es posible que el usuario intente de nuevo cambiar el pass desde la página del link enviado al email
                    $readUserByTempIdResults = $UserTempIdsModel->readUserByTempId($tempId, 'failedAttempts');
                    if ($dedodedPasswordResetToken['errorName'] === 'expired_token' &&  $readUserByTempIdResults) {
                        $user = $UserModel->readUserById($readUserByTempIdResults[0]['id_USERS']);
                        $userId = $user['id_USERS'];

                        //Eliminamos el registro que decía que había un email de desbloqueo pendiente para después renovarlos
                        $UserTempIdsModel->deleteTempIdByUserId($userId, 'failedAttempts');

                        //Recopilamos cfg
                        $cfgOriginEmailIni = $this->iniTool->getKeysAndValues('originEmail');
                        $cfgTokenSettings = $this->iniTool->getKeysAndValues('tokenSettings');
                        $cfgAboutLogin = $this->iniTool->getKeysAndValues('aboutLogin');

                        //Recopilamos datos para el futuro email
                        $tempId = TokenTool::generateUUID();
                        $userRestartData = [
                            'fromEmail' => $cfgOriginEmailIni['email'],
                            'fromPassword' => $cfgOriginEmailIni['password'],
                            'toEmail' => $user['emailAddress'],
                            'lastAttempt' => $user['lastAttempt'],
                            'subject' => 'Cambio de contraseña',
                            'passwordResetLink' => $this->generateLink($cfgAboutLogin['mainControllerLink'], [
                                'passwordResetToken' => TokenTool::generateToken(['id' => $user['id_USERS'], 'type' => 'failedAttempts'], intval($cfgTokenSettings['secondsMaxTimeLifeAccessToken'])),
                                'tempId' => $tempId,
                                'type' => 'failedAttempts',
                                'command' => 'goToPasswordReset'
                            ])
                        ];

                        //Enviamos email con link+token+id temporal
                        if (EmailTool::sendEmail($userRestartData, 'failedAttemptsTemplate')) {
                            //Si el email se ha enviado creamos registro de email de reactivación pendiente
                            $UserTempIdsModel->createTempId($userId, $tempId, 'failedAttempts');
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
                $dedodedPasswordResetToken = TokenTool::decodeAndCheckToken($passwordResetToken, 'forgotPassword');
                if ($dedodedPasswordResetToken['response']) {
                    $userId = $dedodedPasswordResetToken['response']->data->id;
                    //Incorporamos la nueva pass en bbdd
                    $UserModel = new UserModel();
                    $user = $UserModel->readUserById($userId);
                    $response = false;

                    $cfgAboutLogin = $this->iniTool->getKeysAndValues('aboutLogin');
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

        $userData = $this->processData->processData($userData, 'User');
        if (!$userData) {
            return ['response' => false, 'errorCode' => 1];
        }

        //Recopilamos cfg
        $cfgOriginEmailIni = $this->iniTool->getKeysAndValues('originEmail');
        $cfgTokenSettings = $this->iniTool->getKeysAndValues('tokenSettings');
        $cfgAboutLogin = $this->iniTool->getKeysAndValues('aboutLogin');

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

        $userRestartData = [
            'fromEmail' => $cfgOriginEmailIni['email'],
            'fromPassword' => $cfgOriginEmailIni['password'],
            'toEmail' => $user['emailAddress'],
            'subject' => 'Cambio de contraseña por olvido de contraseña original',
            'forgotPasswordLink' => $this->generateLink(
                $cfgAboutLogin['mainControllerLink'],
                [
                    'passwordResetToken' => TokenTool::generateToken(['id' => $user['id_USERS'], 'type' => 'forgotPassword'], intval($cfgTokenSettings['secondsMinTimeLifeForgotPasswordToken'])),
                    'type' => 'forgotPassword',
                    'command' => 'goToPasswordReset'
                ]
            )
        ];

        $isSent = EmailTool::sendEmail($userRestartData, 'forgotPasswordTemplate');
        if ($isSent) {
            if ($UserModel->updateLastForgotPasswordEmailById($user['id_USERS'])) {
                return ['response' => true];
            }
        }
        return ['response' => false, 'errorCode' => 5];
    }

    private function updateCreateTempIdByUserId(int $userId, string $tempId, string $recordCause)
    {
        $UserTempIdsModel = new UserTempIdsModel();
        $UserTempIdsModel->removeTempIdIfExistByIdUser($userId, $recordCause);
        $UserTempIdsModel->createTempId($userId, $tempId, $recordCause);
    }

    private function generateLink(string $base, array $parametres)
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
}
