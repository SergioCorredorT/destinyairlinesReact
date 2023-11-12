<?php
class TokenSanitizer
{
    public static function sanitizeToken(string $token)
    {
        return str_replace('$', '', trim($token));
    }
}
