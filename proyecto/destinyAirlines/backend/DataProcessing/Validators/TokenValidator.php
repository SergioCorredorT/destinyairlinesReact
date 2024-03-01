<?php
class TokenValidator
{
    public static function validateToken(string $token): bool
    {
        // Si el token está vacío, devuelve false
        if (empty($token)) {
            return false;
        }

        return true;
        // Devuelve true si el número de ocurrencias de '.' en el token es exactamente 2
        //return substr_count($token, '.') == 2;
    }

    public static function TokenCaptchaValidator(array $captchaData): bool
    {
        $token = $captchaData['token'];
        $secretCaptchaKey = $captchaData['secretCaptchaKey'];
        if ($token && $secretCaptchaKey) {
            $verificar = file_get_contents("https://hcaptcha.com/siteverify?secret=$secretCaptchaKey&response=$token");
            $respuesta = json_decode($verificar);

            if ($respuesta->success) {
                return true;
            }
        }
        return false;
    }

    public static function validate(array $data): bool | array
    {
        if (isset($data['accessToken']) && !self::validateToken($data['accessToken'])) {
            return false;
        }
        if (isset($data['refreshToken']) && !self::validateToken($data['refreshToken'])) {
            return false;
        }
        if (isset($data['captchaToken']) && !self::TokenCaptchaValidator($data['captchaToken'])) {
            return false;
        }
        if (isset($data['googleToken']) && !self::validateToken($data['googleToken'])) {
            return false;
        }
        return $data;
    }
}
