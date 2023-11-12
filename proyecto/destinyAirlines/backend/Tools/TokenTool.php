<?php
require_once './Tools/IniTool.php';
require_once './vendor/autoload.php';
class TokenTool
{
    public static function generateToken(array $data, int $timeLife = 60 * 60, string $role = 'user')
    {
        $iniTool = new IniTool('./Config/cfg.ini');
        $secretTokenPassword = $iniTool->getKeysAndValues('secretTokenPassword');
        $secret = $secretTokenPassword['secret'];

        if (is_string($data)) {
            $data = ['id' => $data];
        } elseif (!is_array($data)) {
            $data = json_decode(json_encode($data), true);
        }

        $payload = array(
            'iss' => 'destinyAirlines',
            'aud' => 'destinyAirlines',
            'sub' => $data['id'],
            'iat' => time(),
            'exp' => time() + ($timeLife),
            'data' => $data,
            'role' => $role
        );

        $jwt = \Firebase\JWT\JWT::encode($payload, $secret, 'HS256');
        return $jwt;
    }

    public static function checkUpdateAccessTokenDestinyAirlines(string $accessToken)
    {
        require_once './Tools/TokenTool.php';
        require_once './Tools/IniTool.php';
        require_once './Sanitizers/TokenSanitizer.php';
        require_once './Validators/TokenValidator.php';

        $accessToken = TokenSanitizer::sanitizeToken($accessToken);
        if (!TokenValidator::validateToken($accessToken)) {
            return false;
        }
        $iniTool = new IniTool('./Config/cfg.ini');
        $cfgTokenSettings = $iniTool->getKeysAndValues('tokenSettings');
        $secondsMinTimeLifeAccessToken = intval($cfgTokenSettings['secondsMinTimeLifeAccessToken']);
        $secondsMaxTimeLifeAccessToken = intval($cfgTokenSettings['secondsMaxTimeLifeAccessToken']);
        return TokenTool::checkUpdateAccessToken($accessToken, $secondsMinTimeLifeAccessToken, $secondsMaxTimeLifeAccessToken);
    }

    public static function checkUpdateAccessToken(string $accessToken, int $minLifeTimeAccessToken, int $initialLifeTimeAccessToken)
    {
        $payloadAccessToken = TokenTool::decodeAndCheckToken($accessToken, 'access');
        $rsp = [];

        if (isset($payloadAccessToken['errorName'])) {
            $rsp['tokenError'] = $payloadAccessToken['errorName'];
        } else if ($payloadAccessToken['response']) {
            $dataAccessToken = $payloadAccessToken['response']->data;

            $timeRemainingAccessTokenTime = TokenTool::getRemainingTokenTime($accessToken);
            if ($timeRemainingAccessTokenTime < $minLifeTimeAccessToken) {
                $rsp['accessToken'] = TokenTool::generateToken($dataAccessToken, $initialLifeTimeAccessToken);
            }
        }
        return $rsp;
    }

    public static function checkUpdateRefreshToken(string $refreshToken, int $minLifeTimeRefreshToken, int $initialLifeTimeAccessToken, int $initialLifeTimeRefreshToken)
    {
        $payloadRefreshToken = TokenTool::decodeAndCheckToken($refreshToken, 'refresh');
        $rsp = [];

        if (isset($payloadRefreshToken['errorName'])) {
            $rsp['tokenError'] = $payloadRefreshToken['errorName'];
        } else if ($payloadRefreshToken['response']) {
            $dataRefreshToken = $payloadRefreshToken['response']->data;

            $timeRemainingRefreshTokenTime = TokenTool::getRemainingTokenTime($refreshToken);
            if ($timeRemainingRefreshTokenTime < $minLifeTimeRefreshToken) {
                $rsp['refreshToken'] = TokenTool::generateToken($dataRefreshToken, $initialLifeTimeRefreshToken);
                $rsp['accessToken'] = TokenTool::generateToken($dataRefreshToken, $initialLifeTimeAccessToken);
            }
        }
        return $rsp;
    }

    public static function getRemainingTokenTime(string $token)
    {
        try {
            $decodedToken = TokenTool::decodeAndCheckToken($token);
            if ($decodedToken['response']) {
                $remainingTime = $decodedToken['response']->exp - time();
                return $remainingTime;
            }
            return 0;
        } catch (\Firebase\JWT\ExpiredException $e) {
            return 0;
        } catch (\Exception $e) {
            error_log($e->getMessage());
        }
    }

    public static function decodeAndCheckToken(string $token, string $type = '')
    {
        $iniTool = new IniTool('./Config/cfg.ini');
        $secretTokenPassword = $iniTool->getKeysAndValues('secretTokenPassword');
        $secret = $secretTokenPassword['secret'];

        try {
            $headers = new stdClass();
            $headers->alg = 'HS256';
            $headers->typ = 'JWT';

            //Retorna un objeto std
            $decoded = \Firebase\JWT\JWT::decode($token, new \Firebase\JWT\Key($secret, 'HS256'), $headers);
            if (!empty($type)) {
                if (trim($type) != trim($decoded->data->type)) {
                    return ['response' => false, 'errorName' => 'mismatched_type'];
                    //throw new Exception('No coincide el tipo recibido con el tipo de token');
                }
            }
            return ['response' => $decoded];
        } catch (\Firebase\JWT\ExpiredException $e) {
            // El token ha expirado
            return ['response' => false, 'errorName' => 'expired_token'];
        } catch (Exception $e) {
            // Otro error
            return ['response' => false, 'errorName' => 'invalid_token'];
        }
    }

    public static function generateUUID()
    {
        $uuid4 = Ramsey\Uuid\Uuid::uuid4();
        return $uuid4->toString(); // i.e. 25769c6c-d34d-4bfe-ba98-e0ee856f3e7a
    }

    //unused
    public static function getRemainingPayloadTime($payload)
    {
        try {
            if ($payload) {
                $remainingTime = $payload->exp - time();
                return $remainingTime;
            }
            return 0;
        } catch (\Firebase\JWT\ExpiredException $e) {
            return 0;
        } catch (\Exception $e) {
            error_log($e->getMessage());
        }
    }

    //unused
    public static function checkUpdateByRemainingTokenTimes(string $accessToken, string $refreshToken, int $minLifeTimeAccessToken, int $minLifeTimeRefreshToken, int $initialLifeTimeAccessToken, int $initialLifeTimeRefreshToken)
    {
        /*
            Destiny Airlines
                        LifeTime    minTime
            Refresh     7 días ,    1 día
            Access      1 hora,     30 min
        */

        $payloadRefreshToken = TokenTool::decodeAndCheckToken($refreshToken, 'refresh');
        $rsp = [];

        if ($payloadRefreshToken['response']) {
            $dataRefreshToken = $payloadRefreshToken['response']->data;

            $timeRemainingAccessTokenTime = TokenTool::getRemainingTokenTime($accessToken);
            if ($timeRemainingAccessTokenTime < $minLifeTimeAccessToken) {
                $rsp['accessToken'] = TokenTool::generateToken($dataRefreshToken, $initialLifeTimeAccessToken);
            }

            $timeRemainingRefreshTokenTime = TokenTool::getRemainingTokenTime($refreshToken);
            if ($timeRemainingRefreshTokenTime < $minLifeTimeRefreshToken) {
                $rsp['refreshToken'] = TokenTool::generateToken($dataRefreshToken, $initialLifeTimeRefreshToken);
            }
        }
        return $rsp;
    }
}
