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


    public static function TokenCaptchaValidator(string $token, string $claveSecreta): bool
    {
        if ($token && $claveSecreta) {
            $verificar = file_get_contents("https://hcaptcha.com/siteverify?secret=$claveSecreta&response=$token");
            $respuesta = json_decode($verificar);

            if ($respuesta->success) {
                return true;
            }
        }
        return false;
    }
}
