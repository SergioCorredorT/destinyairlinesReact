<?php
require_once ROOT_PATH . '/Controllers/BaseController.php';
require_once ROOT_PATH . '/Sanitizers/goToPasswordResetSanitizer.php';
require_once ROOT_PATH . '/Validators/goToPasswordResetValidator.php';
final class passwordResetController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function goToPasswordReset(array $GET)
    {
        $keys_default = [
            'type' => '',
            'passwordResetToken' => '',
            'tempId' => null
        ];
        foreach ($keys_default as $key => $defaultValue) {
            $goToPasswordResetData[$key] = $GET[$key] ?? $defaultValue;
        }

        require_once ROOT_PATH . '/Sanitizers/goToPasswordResetSanitizer.php';
        require_once ROOT_PATH . '/Validators/goToPasswordResetValidator.php';
        $goToPasswordResetData = GoToPasswordResetSanitizer::sanitize($goToPasswordResetData);

        if (!GoToPasswordResetValidator::validate($goToPasswordResetData)) {
            return false;
        }

        require_once ROOT_PATH . '/Tools/IniTool.php';
        $iniTool = new IniTool(ROOT_PATH  . '/Config/cfg.ini');
        $aboutLogin = $iniTool->getKeysAndValues('aboutLogin');
        $headerLocation = 'Location: '.$aboutLogin['passwordResetLink'].'?type='.$goToPasswordResetData['type'].'&passwordResetToken='.$goToPasswordResetData['passwordResetToken'];
        if(isset($goToPasswordResetData['tempId']) && $goToPasswordResetData['tempId'] !== null)
        {
            $headerLocation.= '&tempId=' . $goToPasswordResetData['tempId'];
        }
        header($headerLocation);
        return true;
    }
}
