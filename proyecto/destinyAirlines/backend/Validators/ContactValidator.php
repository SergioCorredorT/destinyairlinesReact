<?php
class ContactValidator
{
    public static function validateName($name)
    {
        return strlen($name) > 1 ? 0 : 1;
    }

    public static function validateEmail($email)
    {
        if (strlen($email) > 6 && filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return 0;
        } else {
            return 1;
        }
    }

    public static function validateSubject($subject)
    {
        return empty($subject) ? 1 : 0;
    }

    public static function validatePhone($phoneNumber) {
        return strlen($phoneNumber) < 9? 1 : 0;
    }

    public static function validateMessage($message)
    {
        return strlen($message) > 3 ? 0 : 1;
    }

    public static function validate($data)
    {
        $errors = [];

        $nameError = self::validateName($data['name']);
        $nameError != 0 ?
            $errors['name'] = $nameError :
            null;

        $emailError = self::validateEmail($data['email']);
        $emailError != 0 ?
            $errors['email'] = $emailError : 
            null;

        $subjectError = self::validateSubject($data['subject']);
        $subjectError != 0 ?
            $errors['subject'] = $subjectError : 
            null;

        $messageError = self::validateMessage($data['message']);
        $messageError != 0 ?
            $errors['message'] = $messageError : 
            null;

        return $errors;
    }
}
