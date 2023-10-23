<?php
class ContactValidator
{
    public static function validateName($name)
    {
        if (strlen($name) < 2) {
            return false;
        }
        return true;
    }

    public static function validateEmail($email)
    {
        if (strlen($email) < 7 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        return true;
    }

    public static function validateSubject($subject)
    {
        if (empty($subject)) {
            return false;
        }
        return true;
    }

    public static function validatePhone($phoneNumber)
    {
        if (strlen($phoneNumber) < 9) {
            return false;
        }
        return true;
    }

    public static function validateMessage($message)
    {
        if (strlen($message) < 3) {
            return false;
        }
        return true;
    }

    public static function validate($data)
    {
        $isValid = true;

        if (isset($data['name']) && !self::validateName($data['name'])) {
            $isValid = false;
        }
        if (isset($data['email']) && !self::validateEmail($data['email'])) {
            $isValid = false;
        }
        if (isset($data['subject']) && !self::validateSubject($data['subject'])) {
            $isValid = false;
        }
        if (isset($data['message']) && !self::validateMessage($data['message'])) {
            $isValid = false;
        }
        if (isset($data['phoneNumber']) && !self::validatePhone($data['phoneNumber'])) {
            $isValid = false;
        }

        return $isValid;
    }
}
