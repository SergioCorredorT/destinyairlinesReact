<?php
require_once ROOT_PATH . '/Controllers/BaseController.php';
final class TokenController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function checkUpdateAccessToken(array $POST): bool|array
    {
        $accessToken = $POST['accessToken'];

        $accessToken = $this->processData->processData(['accessToken'=>$accessToken], 'Token');
        if (!$accessToken['accessToken']) {
            return false;
        }
        $accessToken=$accessToken['accessToken'];

        $cfgTokenSettings = $this->iniTool->getKeysAndValues('tokenSettings');
        $secondsMinTimeLifeAccessToken = intval($cfgTokenSettings['secondsMinTimeLifeAccessToken']);
        $secondsMaxTimeLifeAccessToken = intval($cfgTokenSettings['secondsMaxTimeLifeAccessToken']);
        return TokenTool::checkUpdateAccessToken($accessToken, $secondsMinTimeLifeAccessToken, $secondsMaxTimeLifeAccessToken);
    }

    public function checkUpdateRefreshToken(array $POST): bool|array
    {
        $refreshToken = $POST['refreshToken'];
        $refreshToken = $this->processData->processData(['refreshToken'=>$refreshToken], 'Token');
        if (!$refreshToken['refreshToken']) {
            return false;
        }
        $refreshToken=$refreshToken['refreshToken'];

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
