<?php
require_once ROOT_PATH . '/Tools/IniTool.php';
require_once ROOT_PATH . '/vendor/autoload.php';
class TokenTool
{
    public static function generateToken(string|array|stdClass $data, int $timeLife = 60 * 60, string $role = 'user'): string
    {
        $jws = self::createJWS($data, $timeLife, $role);
        $encryptedToken = self::encryptToken($jws);

        return $encryptedToken;
    }

    public static function checkUpdateAccessToken(string $accessToken, int $minLifeTimeAccessToken, int $initialLifeTimeAccessToken): array
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

    public static function checkUpdateRefreshToken(string $refreshToken, int $minLifeTimeRefreshToken, int $initialLifeTimeAccessToken, int $initialLifeTimeRefreshToken): array
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
            }
            $dataAccessToken = clone $dataRefreshToken;
            $dataAccessToken->type = 'access';
            $rsp['accessToken'] = TokenTool::generateToken($dataAccessToken, $initialLifeTimeAccessToken);
        }
        return $rsp;
    }

    public static function getRemainingTokenTime(string $token): int
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
            return 0;
        }
    }

    public static function decodeAndCheckToken(string $token, string $type = ''): array
    {
        $iniTool = new IniTool(ROOT_PATH . '/Config/cfg.ini');
        $secretKeys = $iniTool->getKeysAndValues('secretKeys');
        $signatureSecretKey = $secretKeys['signatureSecretKey'];

        // Desencriptar el token
        $token = self::decryptToken($token);

        try {
            $headers = new stdClass();
            $headers->alg = 'HS256';
            $headers->typ = 'JWT';

            //Retorna un objeto std
            $decoded = \Firebase\JWT\JWT::decode($token, new \Firebase\JWT\Key($signatureSecretKey, 'HS256'), $headers);
            if (!empty($type)) {
                if (trim($type) != trim($decoded->data->type)) {
                    return ['response' => false, 'errorName' => 'mismatched_type'];
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

    public static function generateUUID(): string
    {
        $uuid4 = Ramsey\Uuid\Uuid::uuid4();
        return $uuid4->toString(); // i.e. 25769c6c-d34d-4bfe-ba98-e0ee856f3e7a
    }

    public static function createJWS(string|array|stdClass $data, int $timeLife = 60 * 60, string $role = 'user'): string
    {
        $iniTool = new IniTool(ROOT_PATH  . '/Config/cfg.ini');
        $secretKeys = $iniTool->getKeysAndValues('secretKeys');
        $signatureSecretKey = $secretKeys['signatureSecretKey'];

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

        $jws = \Firebase\JWT\JWT::encode($payload, $signatureSecretKey, 'HS256');

        return $jws;
    }

    public static function encryptToken(string $jws): string
    {
        $iniTool = new IniTool(ROOT_PATH  . '/Config/cfg.ini');
        $secretKeys = $iniTool->getKeysAndValues('secretKeys');
        $encryptionSecretKey = $secretKeys['encryptionSecretKey'];

        // Encriptar el token firmado
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
        $encryptedToken = openssl_encrypt($jws, 'aes-256-cbc', $encryptionSecretKey, 0, $iv);

        // El IV se necesita para descifrar el token, así que lo guardamos con el token
        $encryptedToken .= '::' . base64_encode($iv);

        return $encryptedToken;
    }

    public static function decryptToken(string $token): string
    {
        $iniTool = new IniTool(ROOT_PATH . '/Config/cfg.ini');
        $secretKeys = $iniTool->getKeysAndValues('secretKeys');
        $encryptionSecretKey = $secretKeys['encryptionSecretKey'];

        // Desencriptar el token
        list($encryptedToken, $iv) = explode('::', $token);
        $iv = base64_decode($iv);
        $token = openssl_decrypt($encryptedToken, 'aes-256-cbc', $encryptionSecretKey, 0, $iv);

        return $token;
    }
}
