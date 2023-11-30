<?php
class PasswordResetSanitizer
{
    public static function sanitizeType(string $type)
    {
        return htmlspecialchars(trim($type));
    }

    public static function sanitizeTempId(string $tempId)
    {
        return preg_replace('/[^a-zA-Z0-9-ñáéíóúÁÉÍÓÚ ]/', '', $tempId);
    }

    public static function sanitize(array $data)
    {
        //Si es "", o null, o no está definida no se ejecutará el saneamiento
        if (!empty($data['type'])) $data["type"] = self::sanitizeType($data['type']);
        if (!empty($data['tempId'])) $data["tempId"] = self::sanitizeTempId($data['tempId']);

        return $data;
    }
}
