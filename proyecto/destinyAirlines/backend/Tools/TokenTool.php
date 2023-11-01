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

        if (is_string($data)) {
            $data = ["id" => $data];
        } elseif (!is_array($data)) {
            $data = json_decode(json_encode($data), true);
        }

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

        $payloadRefreshToken = TokenTool::decodeAndCheckToken($refreshToken, "refresh");
        $rsp = [];

        if ($payloadRefreshToken["response"]) {
            $dataRefreshToken = $payloadRefreshToken["response"]->data;

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
            $decodedToken = TokenTool::decodeAndCheckToken($token);
            if ($decodedToken["response"]) {
                $remainingTime = $decodedToken["response"]->exp - time();
                return $remainingTime;
            }
            return 0;
        } catch (\Firebase\JWT\ExpiredException $e) {
            return 0;
        } catch (\Exception $e) {
            error_log($e->getMessage());
        }
    }

    public static function decodeAndCheckToken($token, $type = "")
    {
        $iniTool = new IniTool('./Config/cfg.ini');
        $secretTokenPassword = $iniTool->getKeysAndValues("secretTokenPassword");
        $secret = $secretTokenPassword["secret"];

        try {
            $headers = new stdClass();
            $headers->alg = 'HS256';
            $headers->typ = "JWT";

            //Retorna un objeto std
            $decoded = \Firebase\JWT\JWT::decode($token, new \Firebase\JWT\Key($secret, "HS256"), $headers);
            if (!empty($type)) {
                if (trim($type) != trim($decoded->data->type)) {
                    throw new Exception('No coincide el tipo recibido con el tipo de token');
                }
            }
            return ["response" => $decoded];
        } catch (\Firebase\JWT\ExpiredException $e) {
            // El token ha expirado
            return ["response" => false, "errorCode" => 1];
        } catch (\Firebase\JWT\SignatureInvalidException $e) {
            // La firma del token no coincide con la clave proporcionada
            return ["response" => false, "errorCode" => 2];
        } catch (Exception $e) {
            // Otro error
            return ["response" => false, "errorCode" => 3];
        }
    }

    public static function generateUUID() {

        $uuid4 = Ramsey\Uuid\Uuid::uuid4();
        return $uuid4->toString(); // i.e. 25769c6c-d34d-4bfe-ba98-e0ee856f3e7a
    }
}
