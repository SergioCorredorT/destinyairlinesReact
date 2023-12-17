<?php
class TokenSanitizer
{
    public static function sanitizeToken(string $token): string
    {
        return str_replace('$', '', trim($token));
    }
}
