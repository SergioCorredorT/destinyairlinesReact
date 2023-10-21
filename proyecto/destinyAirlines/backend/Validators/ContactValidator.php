<?php
class ContactValidator
{
    public static function validateName($name)
    {
        if (strlen($name) < 2) {
            throw new Exception("Invalid name");
        }
    }

    public static function validateEmail($email)
    {
        if (strlen($email) < 7 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email");
        }
    }

    public static function validateSubject($subject)
    {
        if (empty($subject)) {
            throw new Exception("Invalid subject");
        }
    }

    public static function validatePhone($phoneNumber) {
        if (strlen($phoneNumber) < 9) {
            throw new Exception("Invalid phone number");
        }
    }

    public static function validateMessage($message)
    {
        if (strlen($message) < 3) {
            throw new Exception("Invalid message");
        }
    }

    public static function validate($data)
    {
        self::validateName($data['name']);
        self::validateEmail($data['email']);
        self::validateSubject($data['subject']);
        self::validateMessage($data['message']);
        self::validatePhone($data['phoneNumber']);
    }
}
