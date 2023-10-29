<?php
require_once "./Tools/IniTool.php";
require_once "./vendor/autoload.php";
class TokenTool
{
    public static function generateToken($data, $timeLife = 60 * 60, $role = "user")
    {
        $iniTool = new IniTool('./Config/cfg.ini');
        $secretTokenPassword = $iniTool->getKeysAndValues("secretTokenPassword");
        $secret = $secretTokenPassword["secret"];

        $payload = array(
            "iss" => "destinyAirlines",
            "aud" => "destinyAirlines",
            "sub" => $data["id"],
            "iat" => time(),
            "exp" => time() + ($timeLife),
            "data" => $data,
            "role" => $role
        );

        $jwt = \Firebase\JWT\JWT::encode($payload, $secret, 'HS256');
        return $jwt;
    }
    public static function checkUpdateByRemainingTokenTimes($accessToken, $refreshToken, $minLifeTimeAccessToken, $minLifeTimeRefreshToken, $initialLifeTimeAccessToken, $initialLifeTimeRefreshToken)
    {
        /*
            Destiny Airlines
                        LifeTime    minTime
            Refresh     7 días ,    1 día
            Access      1 hora,     30 min
        */

        $payloadRefreshToken = TokenTool::decodeAndCheckToken($refreshToken);
        $rsp = [];

        if ($payloadRefreshToken) {
            $dataRefreshToken = $payloadRefreshToken->data;

            $timeRemainingAccessTokenTime = TokenTool::getRemainingTokenTime($accessToken);
            if ($timeRemainingAccessTokenTime < $minLifeTimeAccessToken) {
                $rsp["accessToken"] = TokenTool::generateToken($dataRefreshToken, $initialLifeTimeAccessToken);
            }

            $timeRemainingRefreshTokenTime = TokenTool::getRemainingTokenTime($refreshToken);
            if ($timeRemainingRefreshTokenTime < $minLifeTimeRefreshToken) {
                $rsp["refreshToken"] = TokenTool::generateToken($dataRefreshToken, $initialLifeTimeRefreshToken);
            }
        }
        return $rsp;
    }

    public static function getRemainingTokenTime($token)
    {
        try {
            $decoded = TokenTool::decodeAndCheckToken($token);
            if ($decoded) {
                $remainingTime = $decoded->exp - time();
                return $remainingTime;
            }
            return 0;
        } catch (\Firebase\JWT\ExpiredException $e) {
            return 0;
        } catch (\Exception $e) {
            error_log($e->getMessage());
        }
    }

    public static function decodeAndCheckToken($token)
    {
        $iniTool = new IniTool('./Config/cfg.ini');
        $secretTokenPassword = $iniTool->getKeysAndValues("secretTokenPassword"); // Normalmente se recogería de una variable de entorno desde $_ENV
        $secret = $secretTokenPassword["secret"];

        try {
            $headers = new stdClass();
            $headers->alg = 'HS256';
            $headers->typ = "JWT";

            $decoded = \Firebase\JWT\JWT::decode($token, new \Firebase\JWT\Key($secret, "HS256"), $headers);

            return $decoded;
        } catch (\Firebase\JWT\ExpiredException $e) {
            // El token ha expirado
            error_log('El token ha expirado');
            return false;
        } catch (\Firebase\JWT\SignatureInvalidException $e) {
            // La firma del token no coincide con la clave proporcionada
            error_log('La firma del token no coincide con la clave proporcionada');
            return false;
        } catch (Exception $e) {
            // Otro error
            error_log($e->getMessage());
            return false;
        }
    }
}
