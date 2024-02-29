<?php
require_once ROOT_PATH . '/Controllers/BaseController.php';
final class EmailLinkActionController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        parent::loadFilter('EmailLinkAction');
    }

    public function goToPasswordReset(array $GET): bool
    {
        $goToPasswordResetData = $this->filter->filterGoToPasswordResetData($GET);
        $goToPasswordResetData = $this->processData->processData($goToPasswordResetData, 'EmailLinkAction');
        if (!$goToPasswordResetData) {
            return false;
        }

        $aboutLogin = $this->iniTool->getKeysAndValues('aboutLogin');

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
        $goToEmailVerificationData = $this->filter->filterGoToEmailVerificationData($GET);
        $goToEmailVerificationData = $this->processData->processData($goToEmailVerificationData, 'EmailLinkAction');

        $additionalFeatures = $this->iniTool->getKeysAndValues('additionalFeatures');
        $title = 'Activación de cuenta';

        if (!$goToEmailVerificationData) {
            RedirectTool::redirectTo($additionalFeatures['messageUrl'], ['title'=> $title, 'message'=>'Datos recibidos no válidos', 'messageType'=>'error']);
            exit;
        }

        $decodedToken = TokenTool::decodeAndCheckToken($goToEmailVerificationData['emailVerificationToken'], 'emailVerification');
        $UserModel = new UserModel();

        $UserTempIdsModel = new UserTempIdsModel();
        $readUserByTempIdResults = $UserTempIdsModel->readUserByTempId($goToEmailVerificationData['tempId'], 'emailVerification');
        if(!isset($readUserByTempIdResults) || !isset($readUserByTempIdResults[0])) {
            RedirectTool::redirectTo($additionalFeatures['messageUrl'], ['title'=> $title, 'message'=>'Token expirado, inválido o ya usado', 'messageType'=>'error']);
            exit;
        }
        $id_USERS = $readUserByTempIdResults[0]['id_USERS'];

        if (!$decodedToken['response']) {
            if($decodedToken['errorName']==='expired_token') {
                //Si el token ha expirado, puesto que no se enviarán más tokens para una creación de cuenta, se elimina. Y el usuario podrá crear otra nueva
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
        $additionalFeatures = $this->iniTool->getKeysAndValues('additionalFeatures');
        $title = 'Revocación de cuenta no verificada';

        $goToAccountDeletionData = $this->filter->filterGoToAccountDeletionData($GET);
        $goToAccountDeletionData = $this->processData->processData($goToAccountDeletionData, 'EmailLinkAction');
        if (!$goToAccountDeletionData) {
            RedirectTool::redirectTo($additionalFeatures['messageUrl'], ['title'=> $title, 'message'=>'Datos recibidos no válidos', 'messageType'=>'error']);
            exit;
        }

        $decodedToken = TokenTool::decodeAndCheckToken($goToAccountDeletionData['accountDeletionToken'], 'accountDeletion');
        $UserModel = new UserModel();

        if (!$decodedToken['response']) {
            if($decodedToken['errorName']==='expired_token') {
                //Si el token ha expirado, puesto que no se enviarán más tokens para una creación de cuenta, se elimina. Y el usuario podrá crear otra nueva
                $multiModel = new MultiModel();
                $multiModel->deleteUserByTempId($goToAccountDeletionData['tempId'], 'emailVerification');
            }
            RedirectTool::redirectTo($additionalFeatures['messageUrl'], ['title'=> $title, 'message'=>'Token expirado o inválido', 'messageType'=>'error']);
            exit;
        }

        $deleteResponse = $UserModel->deleteUserNoVerifiedById($decodedToken['response']->data->userId);
        if($deleteResponse) {
            RedirectTool::redirectTo($additionalFeatures['messageUrl'], ['title'=> $title, 'message'=>'Cuenta revocada con éxito', 'messageType'=>'success']);
            exit;
        }
        RedirectTool::redirectTo($additionalFeatures['messageUrl'], ['title'=> $title, 'message'=>'La cuenta no ha sido revocada, es posible que ya se haya activado o revocado previamente', 'messageType'=>'error']);
        exit;
    }
}
