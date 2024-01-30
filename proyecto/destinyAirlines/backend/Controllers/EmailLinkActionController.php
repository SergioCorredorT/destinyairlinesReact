<?php
require_once ROOT_PATH . '/Controllers/BaseController.php';
require_once ROOT_PATH . '/Sanitizers/EmailLinkActionSanitizer.php';
require_once ROOT_PATH . '/Validators/EmailLinkActionValidator.php';
final class EmailLinkActionController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function goToPasswordReset(array $GET): bool
    {
        $keys_default = [
            'type' => '',
            'passwordResetToken' => '',
            'tempId' => null
        ];
        foreach ($keys_default as $key => $defaultValue) {
            $goToPasswordResetData[$key] = $GET[$key] ?? $defaultValue;
        }

        $goToPasswordResetData = EmailLinkActionSanitizer::sanitize($goToPasswordResetData);

        if (!EmailLinkActionValidator::validate($goToPasswordResetData)) {
            return false;
        }

        require_once ROOT_PATH . '/Tools/IniTool.php';
        $iniTool = new IniTool(ROOT_PATH  . '/Config/cfg.ini');
        $aboutLogin = $iniTool->getKeysAndValues('aboutLogin');

        require_once ROOT_PATH . '/Tools/RedirectTool.php';

        $params = [
            'type' => $goToPasswordResetData['type'],
            'passwordResetToken' => $goToPasswordResetData['passwordResetToken']
        ];
        
        if(isset($goToPasswordResetData['tempId']) && $goToPasswordResetData['tempId'] !== null) {
            $params['tempId'] = $goToPasswordResetData['tempId'];
        }
        
        RedirectTool::redirectTo($aboutLogin['passwordResetLink'], $params);
        exit;
    }

    public function goToEmailVerification(array $GET): bool
    {
        require_once ROOT_PATH . '/Tools/IniTool.php';
        require_once ROOT_PATH . '/Tools/RedirectTool.php';
        $iniTool = new IniTool(ROOT_PATH  . '/Config/cfg.ini');
        $additionalFeatures = $iniTool->getKeysAndValues('additionalFeatures');
        $title = 'Activación de cuenta';

        $keys_default = [
            'emailVerificationToken' => '',
            'tempId' => '',
            'type' => ''
        ];

        foreach ($keys_default as $key => $defaultValue) {
            $goToEmailVerificationData[$key] = $GET[$key] ?? $defaultValue;
        }

        $goToEmailVerificationData = EmailLinkActionSanitizer::sanitize($goToEmailVerificationData);

        if (!EmailLinkActionValidator::validate($goToEmailVerificationData)) {
            RedirectTool::redirectTo($additionalFeatures['messageUrl'], ['title'=> $title, 'message'=>'Datos recibidos no válidos', 'messageType'=>'error']);
            exit;
        }

        require_once ROOT_PATH . '/Tools/TokenTool.php';
        $decodedToken = TokenTool::decodeAndCheckToken($goToEmailVerificationData['emailVerificationToken'], 'emailVerification');
        $UserModel = new UserModel();

        $UserTempIdsModel = new UserTempIdsModel();
        $readUserByTempIdResults = $UserTempIdsModel->readUserByTempId($goToEmailVerificationData['tempId'], 'emailVerification');
        $id_USERS = $readUserByTempIdResults[0]['id_USERS'];

        if (!$decodedToken['response']) {
            if($decodedToken['errorName']==='expired_token') {
                $UserModel->deleteUserById($id_USERS);
            }
            RedirectTool::redirectTo($additionalFeatures['messageUrl'], ['title'=> $title, 'message'=>'Token expirado o inválido', 'messageType'=>'error']);
            exit;
        }

        if(!$UserModel->updateIsEmailVerified($id_USERS)) {
            RedirectTool::redirectTo($additionalFeatures['messageUrl'], ['title'=> $title, 'message'=>'Error en la verificación de email', 'messageType'=>'error']);
            exit;
        }

        $UserTempIdsModel->deleteTempIdByUserId($id_USERS, 'emailVerification');
        RedirectTool::redirectTo($additionalFeatures['messageUrl'], ['title'=> $title, 'message'=>'Cuenta activada con éxito, puede iniciar sesión en la página principal', 'messageType'=>'success']);
        exit;
    }

    public function goToAccountDeletion(array $GET): bool
    {
        require_once ROOT_PATH . '/Tools/IniTool.php';
        require_once ROOT_PATH . '/Tools/RedirectTool.php';
        $iniTool = new IniTool(ROOT_PATH  . '/Config/cfg.ini');
        $additionalFeatures = $iniTool->getKeysAndValues('additionalFeatures');
        $title = 'Revocación de cuenta no verificada';

        $keys_default = [
            'accountDeletionToken' => '',
            'tempId' => '',
            'type' => ''
        ];

        foreach ($keys_default as $key => $defaultValue) {
            $goToAccountDeletionData[$key] = $GET[$key] ?? $defaultValue;
        }

        $goToAccountDeletionData = EmailLinkActionSanitizer::sanitize($goToAccountDeletionData);

        if (!EmailLinkActionValidator::validate($goToAccountDeletionData)) {
            RedirectTool::redirectTo($additionalFeatures['messageUrl'], ['title'=> $title, 'message'=>'Datos recibidos no válidos', 'messageType'=>'error']);
            exit;
        }

        require_once ROOT_PATH . '/Tools/TokenTool.php';
        $decodedToken = TokenTool::decodeAndCheckToken($goToAccountDeletionData['accountDeletionToken'], 'accountDeletion');
        $UserModel = new UserModel();

        if (!$decodedToken['response']) {
            RedirectTool::redirectTo($additionalFeatures['messageUrl'], ['title'=> $title, 'message'=>'Token expirado o inválido', 'messageType'=>'error']);
            exit;
        }

        $UserModel->deleteUserNoVerifiedById($id_USERS);
        RedirectTool::redirectTo($additionalFeatures['messageUrl'], ['title'=> $title, 'message'=>'Cuenta revocada con éxito', 'messageType'=>'success']);
        exit;
    }
}
