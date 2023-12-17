<?php
class GoToPasswordResetValidator
{
    public static function validateType(string $type): bool
    {
        if (strlen($type) < 2) {
            return false;
        }
        return true;
    }

    public static function validatePasswordResetToken(string $token): bool
    {
        require_once ROOT_PATH . '/Validators/TokenValidator.php';
        $tokenValidator = new TokenValidator();
        if (!$tokenValidator->validateToken($token)) {
            return false;
        }
        return true;
    }

    public static function validateTempId(string $tempId): bool
    {
        if (empty($tempId)) {
            return false;
        }
        return true;
    }


    public static function validate(array $data): bool
    {
        if (isset($data['type']) && !self::validateType($data['type'])) {
            return false;
        }

        if (isset($data['passwordResetToken']) && !self::validatePasswordResetToken($data['passwordResetToken'])) {
            return false;
        }

        if (isset($data['tempId']) && !self::validateTempId($data['tempId'])) {
            return false;
        }

        return true;
    }
}
