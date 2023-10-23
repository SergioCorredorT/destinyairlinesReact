<?php
class UserSanitizer
{
    public static function sanitizeTitle($title)
    {
        return htmlspecialchars(trim($title));
    }

    public static function sanitizeFirstName($firstName)
    {
        return htmlspecialchars(trim($firstName));
    }
    public static function sanitizeLastName($lastName)
    {
        return htmlspecialchars(trim($lastName));
    }
    public static function sanitizeTownCity($townCity)
    {
        return htmlspecialchars(trim($townCity));
    }
    public static function sanitizeStreetAddress($streetAddress)
    {
        return htmlspecialchars(trim($streetAddress));
    }
    public static function sanitizeZipCode($zipCode)
    {
        return htmlspecialchars(trim($zipCode));
    }
    public static function sanitizeCountry($country)
    {
        return htmlspecialchars(trim($country));
    }
    public static function sanitizeEmailAddress($emailAddress)
    {
        return trim($emailAddress);
    }
    public static function sanitizePassword($password)
    {
        return $password;
    }
    public static function sanitizePhoneNumber1($phoneNumber1)
    {
        return htmlspecialchars(trim($phoneNumber1));
    }
    public static function sanitizePhoneNumber2($phoneNumber2)
    {
        return htmlspecialchars(trim($phoneNumber2));
    }
    public static function sanitizePhoneNumber3($phoneNumber3)
    {
        return htmlspecialchars(trim($phoneNumber3));
    }
    public static function sanitizeCompanyName($companyName)
    {
        return htmlspecialchars(trim($companyName));
    }
    public static function sanitizeCompanyTaxNumber($companyTaxNumber)
    {
        return htmlspecialchars(trim($companyTaxNumber));
    }
    public static function sanitizeCompanyPhoneNumber($companyPhoneNumber)
    {
        return htmlspecialchars(trim($companyPhoneNumber));
    }

    public static function sanitize($arrayData)
    {
        $arraySanitized = [];
//Si es "", o null, o no está definida no se ejecutará el saneamiento
        if (!empty($arrayData['title'])) $arraySanitized["title"] = self::sanitizeTitle($arrayData['title']);
        if (!empty($arrayData['firstName'])) $arraySanitized["firstName"] = self::sanitizeFirstName($arrayData['firstName']);
        if (!empty($arrayData['lastName'])) $arraySanitized["lastName"] = self::sanitizeLastName($arrayData['lastName']);
        if (!empty($arrayData['townCity'])) $arraySanitized["townCity"] = self::sanitizeTownCity($arrayData['townCity']);
        if (!empty($arrayData['streetAddress'])) $arraySanitized["streetAddress"] = self::sanitizeStreetAddress($arrayData['streetAddress']);
        if (!empty($arrayData['zipCode'])) $arraySanitized["zipCode"] = self::sanitizeZipCode($arrayData['zipCode']);
        if (!empty($arrayData['country'])) $arraySanitized["country"] = self::sanitizeCountry($arrayData['country']);
        if (!empty($arrayData['emailAddress'])) $arraySanitized["emailAddress"] = self::sanitizeEmailAddress($arrayData['emailAddress']);
        if (!empty($arrayData['password'])) $arraySanitized["password"] = self::sanitizePassword($arrayData['password']);
        if (!empty($arrayData['phoneNumber1'])) $arraySanitized["phoneNumber1"] = self::sanitizePhoneNumber1($arrayData['phoneNumber1']);
        if (!empty($arrayData['phoneNumber2'])) $arraySanitized["phoneNumber2"] = self::sanitizePhoneNumber2($arrayData['phoneNumber2']);
        if (!empty($arrayData['phoneNumber3'])) $arraySanitized["phoneNumber3"] = self::sanitizePhoneNumber3($arrayData['phoneNumber3']);
        if (!empty($arrayData['companyName'])) $arraySanitized["companyName"] = self::sanitizeCompanyName($arrayData['companyName']);
        if (!empty($arrayData['companyTaxNumber'])) $arraySanitized["companyTaxNumber"] = self::sanitizeCompanyTaxNumber($arrayData['companyTaxNumber']);
        if (!empty($arrayData['companyPhoneNumber'])) $arraySanitized["companyPhoneNumber"] = self::sanitizeCompanyPhoneNumber($arrayData['companyPhoneNumber']);
        
        return $arraySanitized;        
    }
}
