<?php
require_once './Sanitizers/TokenSanitizer.php';
require_once './Validators/TokenValidator.php';
class UserValidator
{
    public static function validateTitle($title)
    {
        if ($title != "" && strlen($title) < 3) {
            return false;
        }
        return true;
    }

    public static function validateFirstName($firstName)
    {
        if ($firstName != "" && strlen($firstName) < 2) {
            return false;
        }
        return true;
    }

    public static function validateLastName($lastName)
    {
        if ($lastName != "" && strlen($lastName) < 2) {
            return false;
        }
        return true;
    }

    public static function validateTownCity($townCity)
    {
        if ($townCity != "" && strlen($townCity) < 2) {
            return false;
        }
        return true;
    }

    public static function validateStreetAddress($streetAddress)
    {
        if ($streetAddress != "" && strlen($streetAddress) < 2) {
            return false;
        }
        return true;
    }

    public static function validateZipCode($zipCode)
    {
        if ($zipCode != "" && strlen($zipCode) < 2) {
            return false;
        }
        return true;
    }

    public static function validateCountry($country)
    {
        if ($country != "" && strlen($country) < 2) {
            return false;
        }
        return true;
    }

    public static function validateEmailAddress($emailAddress)
    {
        if (strlen($emailAddress) < 7 || !filter_var($emailAddress, FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        return true;
    }

    public static function validatePassword($password)
    {
        if (strlen($password) < 8) {
            return false;
        }
        return true;
    }

    public static function validatePhoneNumber1($phoneNumber1)
    {
        if ($phoneNumber1 != "" && !preg_match("/^(\+?[0-9]{9,})$/", $phoneNumber1)) {
            return false;
        }
        return true;
    }

    public static function validatePhoneNumber2($phoneNumber1)
    {
        if ($phoneNumber1 != "" && !preg_match("/^(\+?[0-9]{9,})$/", $phoneNumber1)) {
            return false;
        }
        return true;
    }

    public static function validatePhoneNumber3($phoneNumber1)
    {
        if ($phoneNumber1 != "" && !preg_match("/^(\+?[0-9]{9,})$/", $phoneNumber1)) {
            return false;
        }
        return true;
    }
    public static function validateCompanyName($companyName)
    {
        if ($companyName != "" && strlen($companyName) < 2) {
            return false;
        }
        return true;
    }

    public static function validateCompanyTaxNumber($companyTaxNumber)
    {
        if ($companyTaxNumber != "" && strlen($companyTaxNumber) < 2) {
            return false;
        }
        return true;
    }

    public static function validateCompanyPhoneNumber($companyPhoneNumber)
    {
        if ($companyPhoneNumber != "" && !preg_match("/^(\+?[0-9]{9,})$/", $companyPhoneNumber)) {
            return false;
        }
        return true;
    }

    public static function validate($data)
    {
        if (isset($data['title']) && !self::validateTitle($data['title'])) {
            return false;
        }
        if (isset($data['firstName']) && !self::validateFirstName($data['firstName'])) {
            return false;
        }
        if (isset($data['lastName']) && !self::validateLastName($data['lastName'])) {
            return false;
        }
        if (isset($data['townCity']) && !self::validateTownCity($data['townCity'])) {
            return false;
        }
        if (isset($data['streetAddress']) && !self::validateStreetAddress($data['streetAddress'])) {
            return false;
        }
        if (isset($data['zipCode']) && !self::validateZipCode($data['zipCode'])) {
            return false;
        }
        if (isset($data['country']) && !self::validateCountry($data['country'])) {
            return false;
        }
        if (isset($data['emailAddress']) && !self::validateEmailAddress($data['emailAddress'])) {
            return false;
        }
        if (isset($data['password']) && !self::validatePassword($data['password'])) {
            return false;
        }
        if (isset($data['phoneNumber1']) && !self::validatePhoneNumber1($data['phoneNumber1'])) {
            return false;
        }
        if (isset($data['phoneNumber2']) && !self::validatePhoneNumber2($data['phoneNumber2'])) {
            return false;
        }
        if (isset($data['phoneNumber3']) && !self::validatePhoneNumber3($data['phoneNumber3'])) {
            return false;
        }
        if (isset($data['companyName']) && !self::validateCompanyName($data['companyName'])) {
            return false;
        }
        if (isset($data['companyTaxNumber']) && !self::validateCompanyTaxNumber($data['companyTaxNumber'])) {
            return false;
        }
        if (isset($data['companyPhoneNumber']) && !self::validateCompanyPhoneNumber($data['companyPhoneNumber'])) {
            return false;
        }
        if (isset($data['accessToken']) && !TokenValidator::validateToken($data['accessToken'])) {
            return false;
        }
        if (isset($data['refreshToken']) && !TokenValidator::validateToken($data['refreshToken'])) {
            return false;
        }

        return true;
    }
}
