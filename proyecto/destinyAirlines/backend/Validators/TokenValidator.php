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
    
}
