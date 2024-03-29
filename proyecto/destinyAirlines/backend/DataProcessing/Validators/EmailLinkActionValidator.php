<?php
class EmailLinkActionValidator
{
    public static function validateType(string $type): bool
    {
        if (strlen($type) < 2) {
            return false;
        }
        return true;
    }

    public static function validateToken(string $token): bool
    {
        require_once ROOT_PATH . '/DataProcessing/Validators/TokenValidator.php';
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


    public static function validate(array $data): bool | array
    {
        if (isset($data['type']) && !self::validateType($data['type'])) {
            return false;
        }

        if (isset($data['passwordResetToken']) && !self::validateToken($data['passwordResetToken'])) {
            return false;
        }

        if (isset($data['tempId']) && !self::validateTempId($data['tempId'])) {
            return false;
        }

        return $data;
    }
}
