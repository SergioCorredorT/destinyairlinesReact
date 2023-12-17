<?php
require_once ROOT_PATH . '/Sanitizers/TokenSanitizer.php';
class GoToPasswordResetSanitizer
{
    public static function sanitizeType(string $type): string
    {
        return htmlspecialchars(trim($type));
    }

    public static function sanitizePasswordResetToken(string $passwordResetToken): string
    {
        $TokenSanitizer = new TokenSanitizer();
        return $TokenSanitizer->sanitizeToken($passwordResetToken);
    }

    public static function sanitizeTempId(string $tempId): string
    {
        return trim($tempId);
    }

    public static function sanitize(array $data): array
    {
        //Si es '', o null, o no está definida no se ejecutará el saneamiento
        if (!empty($data['type'])) $data['type'] = self::sanitizeType($data['type']);
        if (!empty($data['passwordResetToken'])) $data['passwordResetToken'] = self::sanitizePasswordResetToken($data['passwordResetToken']);
        if (!empty($data['tempId'])) $data['tempId'] = self::sanitizeTempId($data['tempId']);

        return $data;
    }
}
