<?php
require_once ROOT_PATH . '/Controllers/BaseController.php';
require_once ROOT_PATH . '/Sanitizers/TokenSanitizer.php';
require_once ROOT_PATH . '/Validators/TokenValidator.php';
final class TokenController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function checkUpdateAccessToken(array $POST): bool|array
    {
        $accessToken = $POST['accessToken'];
        $accessToken = TokenSanitizer::sanitizeToken($accessToken);
        if(!TokenValidator::validateToken($accessToken))
        {
            return false;
        }
        $cfgTokenSettings = $this->iniTool->getKeysAndValues('tokenSettings');
        $secondsMinTimeLifeAccessToken = intval($cfgTokenSettings['secondsMinTimeLifeAccessToken']);
        $secondsMaxTimeLifeAccessToken = intval($cfgTokenSettings['secondsMaxTimeLifeAccessToken']);
        return TokenTool::checkUpdateAccessToken($accessToken, $secondsMinTimeLifeAccessToken, $secondsMaxTimeLifeAccessToken);
    }

    public function checkUpdateRefreshToken(array $POST): bool|array
    {
        $refreshToken = $POST['refreshToken'];
        $refreshToken = TokenSanitizer::sanitizeToken($refreshToken);
        if(!TokenValidator::validateToken($refreshToken))
        {
            return false;
        }
        $cfgTokenSettings = $this->iniTool->getKeysAndValues('tokenSettings');
        $secondsMinTimeLifeRefreshToken = intval($cfgTokenSettings['secondsMinTimeLifeRefreshToken']);
        $secondsMaxTimeLifeRefreshToken = intval($cfgTokenSettings['secondsMaxTimeLifeRefreshToken']);
        $secondsMaxTimeLifeAccessToken = intval($cfgTokenSettings['secondsMaxTimeLifeAccessToken']);
        return TokenTool::checkUpdateRefreshToken($refreshToken, $secondsMinTimeLifeRefreshToken, $secondsMaxTimeLifeAccessToken, $secondsMaxTimeLifeRefreshToken);
    }

    public function getUpdateTime(): int
    {
        $tokenSettings = $this->iniTool->getKeysAndValues('tokenSettings');
        return $tokenSettings['autoUpdateTokenTime'];
    }

    public function getSiteKey(): string
    {
        $secretKeys = $this->iniTool->getKeysAndValues('secretKeys');
        return $secretKeys['captchaSiteKey'];
    }
}
