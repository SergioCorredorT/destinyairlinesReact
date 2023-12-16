<?php
require_once ROOT_PATH . '/Controllers/BaseController.php';
require_once ROOT_PATH . '/Tools/TokenTool.php';
require_once ROOT_PATH . '/Tools/IniTool.php';
require_once ROOT_PATH . '/Sanitizers/TokenSanitizer.php';
require_once ROOT_PATH . '/Validators/TokenValidator.php';
final class TokenController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function checkUpdateAccessToken($POST)
    {
        $accessToken = $POST['accessToken'];
        $accessToken = TokenSanitizer::sanitizeToken($accessToken);
        if(!TokenValidator::validateToken($accessToken))
        {
            return false;
        }
        $iniTool = new IniTool(ROOT_PATH  . '/Config/cfg.ini');
        $cfgTokenSettings = $iniTool->getKeysAndValues('tokenSettings');
        $secondsMinTimeLifeAccessToken = intval($cfgTokenSettings['secondsMinTimeLifeAccessToken']);
        $secondsMaxTimeLifeAccessToken = intval($cfgTokenSettings['secondsMaxTimeLifeAccessToken']);
        return TokenTool::checkUpdateAccessToken($accessToken, $secondsMinTimeLifeAccessToken, $secondsMaxTimeLifeAccessToken);
    }

    public function checkUpdateRefreshToken($POST)
    {
        $refreshToken = $POST['refreshToken'];
        $refreshToken = TokenSanitizer::sanitizeToken($refreshToken);
        if(!TokenValidator::validateToken($refreshToken))
        {
            return false;
        }
        $iniTool = new IniTool(ROOT_PATH  . '/Config/cfg.ini');
        $cfgTokenSettings = $iniTool->getKeysAndValues('tokenSettings');
        $secondsMinTimeLifeRefreshToken = intval($cfgTokenSettings['secondsMinTimeLifeRefreshToken']);
        $secondsMaxTimeLifeRefreshToken = intval($cfgTokenSettings['secondsMaxTimeLifeRefreshToken']);
        $secondsMaxTimeLifeAccessToken = intval($cfgTokenSettings['secondsMaxTimeLifeAccessToken']);
        return TokenTool::checkUpdateRefreshToken($refreshToken, $secondsMinTimeLifeRefreshToken, $secondsMaxTimeLifeAccessToken, $secondsMaxTimeLifeRefreshToken);
    }
}
