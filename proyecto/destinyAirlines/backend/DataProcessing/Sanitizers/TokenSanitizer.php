<?php
class TokenSanitizer
{
    //Si al parámetro se le pone que es de tipo string, en las llamadas a esta función, vscode se quejará de que no se le pasa un string a pesar de ser string¡¡!!
    public static function sanitizeToken(string $token): string
    {
        return trim($token);
    }

    public static function sanitize(array $data): array
    {
        //Si es "", o null, o no está definida no se ejecutará el saneamiento
        if (!empty($data['accessToken'])) $data["accessToken"] = self::sanitizeToken($data['accessToken']);
        if (!empty($data['refreshToken'])) $data["refreshToken"] = self::sanitizeToken($data['refreshToken']);
        if (!empty($data['googleToken'])) $data["googleToken"] = self::sanitizeToken($data['googleToken']);

        return $data;
    }
}

