<?php
class ContactValidator
{
    public static function validateName(string $name): bool
    {
        if (strlen($name) < 2) {
            return false;
        }
        return true;
    }

    public static function validateEmail(string $email): bool
    {
        if (strlen($email) < 7 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        return true;
    }

    public static function validateSubject(string $subject): bool
    {
        if (empty($subject)) {
            return false;
        }
        return true;
    }

    public static function validatePhone(string $phoneNumber): bool
    {
        if (strlen($phoneNumber) < 9) {
            return false;
        }
        return true;
    }

    public static function validateMessage(string $message): bool
    {
        if (strlen($message) < 3) {
            return false;
        }
        return true;
    }

    public static function validate(array $data): bool
    {
        if (isset($data['name']) && !self::validateName($data['name'])) {
            return false;
        }
        if (isset($data['email']) && !self::validateEmail($data['email'])) {
            return false;
        }
        if (isset($data['subject']) && !self::validateSubject($data['subject'])) {
            return false;
        }
        if (isset($data['message']) && !self::validateMessage($data['message'])) {
            return false;
        }
        if (isset($data['phoneNumber']) && !self::validatePhone($data['phoneNumber'])) {
            return false;
        }

        return true;
    }
}
