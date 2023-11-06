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

    public static function sanitize(array $data)
    {
        $arraySanitized = [];
//Si es "", o null, o no está definida no se ejecutará el saneamiento
        if (!empty($data['title'])) $arraySanitized["title"] = self::sanitizeTitle($data['title']);
        if (!empty($data['firstName'])) $arraySanitized["firstName"] = self::sanitizeFirstName($data['firstName']);
        if (!empty($data['lastName'])) $arraySanitized["lastName"] = self::sanitizeLastName($data['lastName']);
        if (!empty($data['townCity'])) $arraySanitized["townCity"] = self::sanitizeTownCity($data['townCity']);
        if (!empty($data['streetAddress'])) $arraySanitized["streetAddress"] = self::sanitizeStreetAddress($data['streetAddress']);
        if (!empty($data['zipCode'])) $arraySanitized["zipCode"] = self::sanitizeZipCode($data['zipCode']);
        if (!empty($data['country'])) $arraySanitized["country"] = self::sanitizeCountry($data['country']);
        if (!empty($data['emailAddress'])) $arraySanitized["emailAddress"] = self::sanitizeEmailAddress($data['emailAddress']);
        if (!empty($data['password'])) $arraySanitized["password"] = self::sanitizePassword($data['password']);
        if (!empty($data['phoneNumber1'])) $arraySanitized["phoneNumber1"] = self::sanitizePhoneNumber1($data['phoneNumber1']);
        if (!empty($data['phoneNumber2'])) $arraySanitized["phoneNumber2"] = self::sanitizePhoneNumber2($data['phoneNumber2']);
        if (!empty($data['phoneNumber3'])) $arraySanitized["phoneNumber3"] = self::sanitizePhoneNumber3($data['phoneNumber3']);
        if (!empty($data['companyName'])) $arraySanitized["companyName"] = self::sanitizeCompanyName($data['companyName']);
        if (!empty($data['companyTaxNumber'])) $arraySanitized["companyTaxNumber"] = self::sanitizeCompanyTaxNumber($data['companyTaxNumber']);
        if (!empty($data['companyPhoneNumber'])) $arraySanitized["companyPhoneNumber"] = self::sanitizeCompanyPhoneNumber($data['companyPhoneNumber']);
        
        return $arraySanitized;        
    }
}
