<?php
class GoToPasswordResetValidator
{
    public static function validateType($type)
    {
        if (strlen($type) < 2) {
            return false;
        }
        return true;
    }

    public static function validatePasswordResetToken($token)
    {
        require_once ROOT_PATH . '/Validators/TokenValidator.php';
        $tokenValidator = new TokenValidator();
        if (!$tokenValidator->validateToken($token)) {
            return false;
        }
        return true;
    }

    public static function validateTempId($tempId)
    {
        if (empty($tempId)) {
            return false;
        }
        return true;
    }


    public static function validate($data)
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
