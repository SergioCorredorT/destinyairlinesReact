<?php
class TokenSanitizer
{
    //Si al parámetro se le pone que es de tipo string, en las llamadas a esta función, vscode se quejará de que no se le pasa un string a pesar de ser string¡¡!!
    public static function sanitizeToken($token): string
    {
        return trim($token);
    }
}
