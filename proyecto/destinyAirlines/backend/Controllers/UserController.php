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

    public function getUserEditableInfo(array $POST)
    {
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

        $captchaToken = $this->processData->processData(['captchaToken' => ['token' => $userData['captchaToken'], 'secretCaptchaKey' => $secretKeys['captchaSecretKey']]], 'Token');
        if (!$captchaToken) {
            return ['response' => false, 'message' => 'Validación de captcha incorrecta'];
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
        $tempId = TokenTool::generateUUID();

        $userVerificationData = $this->generateVerfificationEmailData($userId, $userData['emailAddress'], $tempId);

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

    public function googleCreateUser(array $POST)
    {
        $userData = $this->filter->filterGoogleCreateUserData($POST);

        $userData = $this->processData->processData($userData, 'User');
        if (!$userData) {
            return  ['response' => false, 'message' => 'No se pudo crear la cuenta. El formato de los datos recibidos es inválido.'];
        }

        $credential = $userData['credentialResponse']['credential'];
        $clientId = $userData['credentialResponse']['clientId'];
        $payload = $this->checkGetGoogleTokenData(['googleToken' => $credential, 'googleClientId' => $clientId]);
        if (!$payload || !$payload['email_verified']) {
            return ['response' => false, 'message' => 'Validación de token de Google incorrecta'];
        }

        $userData['emailAddress'] = $payload['email'];
        unset($userData['credentialResponse']);

        $userData['passwordHash'] = password_hash($userData['password'], PASSWORD_BCRYPT);
        unset($userData['password']);

        $UserModel = new UserModel();
        $userData['isEmailVerified'] = 1;
        $userId = $UserModel->createUser($userData, true);
        if (!$userId) {
            return  ['response' => false, 'message' => 'No se ha podido crear la cuenta. Esto puede deberse a un error del servidor o a que ya hay una cuenta registrada con ese email o a que ya se haya enviado un correo electrónico de activación de cuenta. Por favor, revisa tu correo electrónico para obtener instrucciones detalladas. Si no reconoces la solicitud de creación de la cuenta, por favor, haz clic en el enlace de revocación de la cuenta que se proporciona en el correo electrónico.'];
        }
        $userId = $userId[0];

        $welcomeData = $this->generateWelcomeData($userData['emailAddress']);
        EmailTool::sendEmail($welcomeData, 'welcomeTemplate');

        return  [...$this->googleUserLoginAfterCreate($userData['emailAddress']), 'message' => 'Te has registrado y has iniciado sesión con éxito. ¡Bienvenido!.'];
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

        $userChangePasswordData = $this->generateUserChangePasswordData($email);

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

        $accountDeletionData = $this->generateAccountDeletionData($email);
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

        $user = $UserModel->readUserVerifiedByEmail($userData['emailAddress']);

        if (!$user) {
            return ['response' => false];
        }

        //El valor inicial de intentos de login es 0, entonces cada vez que iniciemos un intento le daremos un + 1 al que ya había
        $user['currentLoginAttempts'] = intval($user['currentLoginAttempts']) + 1;

        //Si estamos dentro del número de intentos permitidos comprobamos el pass
        if ($this->isLoginAttemptValid($user, $userData['password'])) {
            $UserModel->updateResetCurrentLoginAttempts($user['id_USERS']);
            $loginTokens = $this->generateLoginTokens($user['id_USERS']);
            return $this->generateSuccessLoginResponse($user, $loginTokens);
            //Si hemos fallado contraseña o si estamos fuera del rango de intentos comprobamos si debemos mandar el email al usuario
        } else {
            return $this->handleFailedLoginAttempt($user);
        }
    }

    public function googleLoginUser(array $POST)
    {
        $secretKeys = $this->iniTool->getKeysAndValues('secretKeys');
        $userData = $this->filter->filterGoogleLoginUserData($POST);
        $userData = $this->processData->processData(['googleToken' => $userData['googleToken'], 'googleClientId' => $secretKeys['googleClientId']], 'Token');
        $payload = $this->checkGetGoogleTokenData($userData);
        if (!$payload || !$payload['email_verified']) {
            return ['response' => false, 'message' => 'Validación de token de Google incorrecta'];
        }

        $UserModel = new UserModel();
        $user = $UserModel->readUserVerifiedByEmail($payload['email']);
        if (!$user) {
            return ['response' => false];
        }
        if (intval($user['currentLoginAttempts']) > 0) {
            $UserModel->updateResetCurrentLoginAttempts($user['id_USERS']);
        }

        ['accessToken' => $accessToken, 'refreshToken' => $refreshToken] = $this->generateGoogleLoginTokens($user);

        return $this->generateSuccessGoogleLoginResponse($user, $accessToken, $refreshToken);
    }

    private function googleUserLoginAfterCreate(string $email)
    {
        $UserModel = new UserModel();
        $user = $UserModel->readUserVerifiedByEmail($email);

        if (!$user) {
            return false;
        }
        ['accessToken' => $accessToken, 'refreshToken' => $refreshToken] = $this->generateGoogleLoginTokens($user);

        return $this->generateSuccessGoogleLoginResponse($user, $accessToken, $refreshToken);
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
            return match ($POST['type']) {
                'failedAttempts' => $this->passwordResetFailedAttempts($userData),
                'forgotPassword' => $this->passwordResetForgotPassword($userData),
                default => null,
            };
        }
    }

    public function passwordResetFailedAttempts(array $POST)
    {
        $new_password = $POST['new_password'];
        $passwordResetToken = $POST['passwordResetToken'];
        $tempId = $POST['tempId'];
        $UserModel = new UserModel();
        $UserTempIdsModel = new UserTempIdsModel();

        try {
            $dedodedPasswordResetToken = TokenTool::decodeAndCheckToken($passwordResetToken, 'failedAttempts');
            if ($dedodedPasswordResetToken['response']) {
                $userId = $dedodedPasswordResetToken['response']->data->id;
                //Comprobamos si no hay un email de desbloqueo pendiente
                if ($UserTempIdsModel->readUserByUserId($userId, 'failedAttempts')) {
                    //Eliminamos el registro que decía que había un email de desbloqueo pendiente
                    $UserTempIdsModel->deleteTempIdByUserId($userId, 'failedAttempts');

                    //Reiniciamos el contador de intentos
                    $UserModel->updateResetCurrentLoginAttempts($userId);

                    //Incorporamos la nueva pass en bbdd
                    $UserModel->updatePasswordHashById('' . password_hash($new_password, PASSWORD_BCRYPT) . '', $userId);

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

                    //Recopilamos datos para el futuro email
                    $tempId = TokenTool::generateUUID();
                    $userFailedAttemptsRestartData = $this->generateUserFailedAttemptsRestartData($user, $tempId);

                    //Enviamos email con link+token+id temporal
                    if (EmailTool::sendEmail($userFailedAttemptsRestartData, 'failedAttemptsTemplate')) {
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

    public function passwordResetForgotPassword(array $POST)
    {
        $new_password = $POST['new_password'];
        $passwordResetToken = $POST['passwordResetToken'];
        $UserModel = new UserModel();
        $UserTempIdsModel = new UserTempIdsModel();

        try {
            $dedodedPasswordResetToken = TokenTool::decodeAndCheckToken($passwordResetToken, 'forgotPassword');
            if ($dedodedPasswordResetToken['response']) {
                $userId = $dedodedPasswordResetToken['response']->data->id;
                //Incorporamos la nueva pass en bbdd
                $user = $UserModel->readUserById($userId);
                $response = false;

                //Comprobamos si se ha superado el límite de intentos de inicio de sesión fallidos desde que se le envió email al usuario hasta que ha clicado en el link de forgotPassword
                if ($UserTempIdsModel->readUserByUserId($userId, 'failedAttempts')) {
                    return ['response' => false, 'message' => '<span class="warning">No se pudo actualizar la contraseña. Se ha detectado que se le ha enviado previamente un email para reactivación de cuenta con motivo de demasiados intentos fallidos de inicio de sesión.</span>'];
                }

                $cfgAboutLogin = $this->iniTool->getKeysAndValues('aboutLogin');
                //Comprobamos que la cuenta no esté bloqueada por exceder el máximo de intentos permitidos de login
                if ($user || intval($user['currentLoginAttempts']) < intval($cfgAboutLogin['maxLoginAttemps'])) {
                    $response = $UserModel->updatePasswordHashById('' . password_hash($new_password, PASSWORD_BCRYPT . ''), $userId);
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

        if (!is_null($user['lastForgotPasswordEmail'])) {
            if ($this->isForgotPasswordTokenExpired($user['lastForgotPasswordEmail'])) {
                return ['response' => false, 'errorCode' => 4];
            }
        }

        $userForgotPasswordRestartData = $this->generateUserForgotPasswordRestartData($user);

        $isSent = EmailTool::sendEmail($userForgotPasswordRestartData, 'forgotPasswordTemplate');
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

    public static function checkGetGoogleTokenData(array $googleData)
    {
        $idToken = $googleData['googleToken'];
        $clientId = $googleData['googleClientId'];

        require_once ROOT_PATH . '/vendor/autoload.php';
        $keys = Firebase\JWT\JWK::parseKeySet(
            json_decode(file_get_contents('https://www.googleapis.com/oauth2/v3/certs'), true)
        );
        $headers = null;
        $data = Firebase\JWT\JWT::decode($idToken, $keys, $headers);

        if ($data->aud != $clientId) {
            return false;
        }

        //Retornamos un array asociativo
        return json_decode(json_encode($data), true);
    }

    private function generateLoginTokens($userId)
    {
        $cfgTokenSettings = $this->iniTool->getKeysAndValues('tokenSettings');
        $secondsMaxTimeLifeAccessToken = intval($cfgTokenSettings['secondsMaxTimeLifeAccessToken']);
        $secondsMaxTimeLifeRefreshToken = intval($cfgTokenSettings['secondsMaxTimeLifeRefreshToken']);

        $accessToken = TokenTool::generateToken(['id' => $userId, 'type' => 'access'], $secondsMaxTimeLifeAccessToken);
        $refreshToken = TokenTool::generateToken(['id' => $userId, 'type' => 'refresh'], $secondsMaxTimeLifeRefreshToken);

        return [
            'accessToken' => $accessToken,
            'refreshToken' => $refreshToken
        ];
    }

    private function generateFailedAttemptsToken($userId)
    {
        $cfgTokenSettings = $this->iniTool->getKeysAndValues('tokenSettings');
        $secondsMaxTimeLifeAccessToken = intval($cfgTokenSettings['secondsMaxTimeLifeAccessToken']);

        $failedAttemptsToken = TokenTool::generateToken(['id' => $userId, 'type' => 'failedAttempts'], $secondsMaxTimeLifeAccessToken);

        return $failedAttemptsToken;
    }

    private function generateSuccessLoginResponse($user, $loginTokens)
    {
        return [
            'tokens' => [
                'accessToken' => $loginTokens['accessToken'],
                'refreshToken' => $loginTokens['refreshToken']
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
    }

    private function generatePasswordResetLink($passwordResetToken, $type, $tempId)
    {
        $cfgAboutLogin = $this->iniTool->getKeysAndValues('aboutLogin');
        return $this->generateLink(
            $cfgAboutLogin['mainControllerLink'],
            [
                'passwordResetToken' => $passwordResetToken,
                'tempId' => $tempId,
                'type' => $type,
                'command' => 'goToPasswordReset'
            ]
        );
    }

    private function generateEmailDataForFailedAttempts($user)
    {
        $cfgOriginEmailIni = $this->iniTool->getKeysAndValues('originEmail');
        return [
            'fromEmail' => $cfgOriginEmailIni['email'],
            'fromPassword' => $cfgOriginEmailIni['password'],
            'toEmail' => $user['emailAddress'],
            'lastAttempt' => $user['lastAttempt'],
            'subject' => 'Cambio de contraseña'
        ];
    }

    private function sendEmailAndCreateTempId($user, $userRestartData, $tempId)
    {
        $isEmailSent = EmailTool::sendEmail($userRestartData, 'failedAttemptsTemplate');
        if ($isEmailSent) {
            //Si el email se ha enviado creamos registro como que hay un email de reactivación pendiente
            $this->updateCreateTempIdByUserId($user['id_USERS'], $tempId, 'failedAttempts');
            return true;
        } else {
            return false;
        }
    }

    private function isLoginAttemptValid($user, $password)
    {
        $cfgAboutLogin = $this->iniTool->getKeysAndValues('aboutLogin');
        $maxLoginAttemps = intval($cfgAboutLogin['maxLoginAttemps']);

        return intval($user['currentLoginAttempts']) <= $maxLoginAttemps && password_verify($password, $user['passwordHash']);
    }

    private function handleFailedLoginAttempt($user)
    {
        $cfgAboutLogin = $this->iniTool->getKeysAndValues('aboutLogin');
        $maxLoginAttemps = intval($cfgAboutLogin['maxLoginAttemps']);
        // En BBDD solo actualizamos si es necesario
        if (intval($user['currentLoginAttempts']) <= $maxLoginAttemps) {
            $UserModel = new UserModel();
            $UserModel->updateAddCurrentLoginAttempts($user['id_USERS']);
            $user['lastAttempt'] = date('Y-m-d H:i:s');
        }
        //si el número de intento actual coincide con el último permitido, se envía email
        if (intval($user['currentLoginAttempts']) === $maxLoginAttemps) {
            return $this->sendFailedAttemptsEmail($user);
        }
        //Si no hemos sobrepasado el número de intentos máximo y hemos fallado contraseña
        return ['response' => false, 'currentLoginAttempts' => $user['currentLoginAttempts'], 'lastAttempt' => $user['lastAttempt']];
    }

    private function sendFailedAttemptsEmail($user)
    {
        $userRestartData = $this->generateEmailDataForFailedAttempts($user);
        $failedAttemptsToken = $this->generateFailedAttemptsToken($user['id_USERS']);

        //Insertar una id temporal en bbdd para enviarlo integrado en un link al email del user para evitar usar la id original del usuario.
        //Esta id temporal sirve por si el token del link que se enviará por email caduca sin usarse, y entonces poder identificar de nuevo 
        //  la dirección email del usuario.
        $tempId = TokenTool::generateUUID();
        //Generamos link que se enviará por email
        $userRestartData['passwordResetLink'] = $this->generatePasswordResetLink($failedAttemptsToken, 'failedAttempts', $tempId);

        if (!$this->sendEmailAndCreateTempId($user, $userRestartData, $tempId)) {
            if (!$this->sendEmailAndCreateTempId($user, $userRestartData, $tempId)) {
                return ['response' => false, 'currentLoginAttempts' => $user['currentLoginAttempts'], 'lastAttempt' => $user['lastAttempt'], 'emailSent' => false, 'message' => 'Hubo un error en el envío de email. Por favor, póngase en contacto con los administradores'];
            }
        }
        return ['response' => false, 'currentLoginAttempts' => $user['currentLoginAttempts'], 'lastAttempt' => $user['lastAttempt'], 'emailSent' => true];
    }

    private function generateVerfificationEmailData($userId, $destinyEmailAddress, $tempId)
    {
        $cfgTokenSettings = $this->iniTool->getKeysAndValues('tokenSettings');
        $secondsTimeLifeActivationAccount = intval($cfgTokenSettings['secondsTimeLifeActivationAccount']);
        $cfgOriginEmailIni = $this->iniTool->getKeysAndValues('originEmail');
        $cfgAboutLogin = $this->iniTool->getKeysAndValues('aboutLogin');
        return [
            'fromEmail' => $cfgOriginEmailIni['email'],
            'fromPassword' => $cfgOriginEmailIni['password'],
            'toEmail' => $destinyEmailAddress,
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
    }

    private function generateUserChangePasswordData($email)
    {
        $cfgOriginEmailIni = $this->iniTool->getKeysAndValues('originEmail');
        return [
            'fromEmail' => $cfgOriginEmailIni['email'],
            'fromPassword' => $cfgOriginEmailIni['password'],
            'toEmail' => $email,
            'subject' => 'Cambio de contraseña'
        ];
    }

    private function generateAccountDeletionData($email)
    {
        $cfgOriginEmailIni = $this->iniTool->getKeysAndValues('originEmail');
        return [
            'fromEmail' => $cfgOriginEmailIni['email'],
            'fromPassword' => $cfgOriginEmailIni['password'],
            'toEmail' => $email,
            'subject' => 'Eliminación de cuenta'
        ];
    }

    private function generateSuccessGoogleLoginResponse($user, $accessToken, $refreshToken)
    {
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
                    'dateBirth' => $user['dateBirth'],
                    'emailAddress' => $user['emailAddress']
                ]
            ]
        ];
    }

    private function generateGoogleLoginTokens($user)
    {
        $cfgTokenSettings = $this->iniTool->getKeysAndValues('tokenSettings');
        $secondsMaxTimeLifeAccessToken = intval($cfgTokenSettings['secondsMaxTimeLifeAccessToken']);
        $secondsMaxTimeLifeRefreshToken = intval($cfgTokenSettings['secondsMaxTimeLifeRefreshToken']);

        $accessToken = TokenTool::generateToken(['id' => $user['id_USERS'], 'type' => 'access'], $secondsMaxTimeLifeAccessToken);
        $refreshToken = TokenTool::generateToken(['id' => $user['id_USERS'], 'type' => 'refresh'], $secondsMaxTimeLifeRefreshToken);
        return [
            'accessToken' => $accessToken,
            'refreshToken' => $refreshToken
        ];
    }

    private function generateUserFailedAttemptsRestartData($user, $tempId)
    {
        $cfgOriginEmailIni = $this->iniTool->getKeysAndValues('originEmail');
        $cfgTokenSettings = $this->iniTool->getKeysAndValues('tokenSettings');
        $cfgAboutLogin = $this->iniTool->getKeysAndValues('aboutLogin');

        return [
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
    }

    private function generateUserForgotPasswordRestartData($user)
    {
        $cfgOriginEmailIni = $this->iniTool->getKeysAndValues('originEmail');
        $cfgTokenSettings = $this->iniTool->getKeysAndValues('tokenSettings');
        $cfgAboutLogin = $this->iniTool->getKeysAndValues('aboutLogin');

        return [
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
    }

    private function isForgotPasswordTokenExpired($lastForgotPasswordEmail)
    {
        $cfgTokenSettings = $this->iniTool->getKeysAndValues('tokenSettings');
        $expireTimeTokenForgotPassword = intval($cfgTokenSettings['secondsMinTimeLifeForgotPasswordToken']);

        if ($this->isTokenExpired($lastForgotPasswordEmail, $expireTimeTokenForgotPassword)) {
            return true;
        }
        return false;
    }

    private function isTokenExpired($startTime, $expireTime)
    {
        $interval = $this->calculateTimeDifferenceSinceNow($startTime);
        return $interval < intval($expireTime);
    }

    private function calculateTimeDifferenceSinceNow($startTime)
    {
        $now = new DateTime();
        $start = new DateTime($startTime);
        $interval = $now->getTimestamp() - $start->getTimestamp();
        return $interval;
    }

    private function generateWelcomeData($destinyEmailAddress)
    {
        $cfgOriginEmailIni = $this->iniTool->getKeysAndValues('originEmail');
        return [
            'fromEmail' => $cfgOriginEmailIni['email'],
            'fromPassword' => $cfgOriginEmailIni['password'],
            'toEmail' => $destinyEmailAddress,
            'subject' => 'Creación de cuenta exitosa'
        ];
    }
}
