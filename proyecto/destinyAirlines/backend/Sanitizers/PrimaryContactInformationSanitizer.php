<?php
class PrimaryContactInformationSanitizer
{
    public static function sanitizeDocumentationType($documentationType)
    {
        return htmlspecialchars(trim($documentationType));
    }

    public static function sanitizeDocumentCode($documentCode)
    {
        // Aquí va tu código de saneamiento para 'documentCode'
        return htmlspecialchars(trim($documentCode));
    }

    public static function sanitizeExpirationDate($expirationDate)
    {
        // Elimina todos los caracteres que no sean números, guiones o barras
        $sanitizedExpirationDate = preg_replace('/[^0-9\-\/]/', '', $expirationDate);
        return $sanitizedExpirationDate;
    }

    public static function sanitizeTitle($title)
    {
        // Elimina todos los números
        $sanitizedTitle = preg_replace('/[0-9]/', '', $title);
        return htmlspecialchars(trim($sanitizedTitle));
    }


    public static function sanitizeFirstName($firstName)
    {
        $sanitizedTitle = preg_replace('/[0-9]/', '', $firstName);
        return htmlspecialchars(trim($sanitizedTitle));
    }

    public static function sanitizeLastName($lastName)
    {
        // Elimina todos los números
        $sanitizedLastName = preg_replace('/[0-9]/', '', $lastName);
        return htmlspecialchars(trim($sanitizedLastName));
    }

    public static function sanitizeEmailAddress($emailAddress)
    {
        return trim($emailAddress);
    }

    public static function sanitizePhoneNumber1($phoneNumber1)
    {
        return htmlspecialchars(trim($phoneNumber1));
    }

    public static function sanitizePhoneNumber2($phoneNumber2)
    {
        return htmlspecialchars(trim($phoneNumber2));
    }

    public static function sanitizeCountry($country)
    {
        // Elimina todos los números
        $sanitizedCountry = preg_replace('/[0-9]/', '', $country);
        return htmlspecialchars(trim($sanitizedCountry));
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

    public static function sanitizeDateBirth($dateBirth)
    {
        // Elimina todos los caracteres que no sean números, guiones o barras
        $sanitizedDateBirth = preg_replace('/[^0-9\-\/]/', '', $dateBirth);
        return $sanitizedDateBirth;
    }

    public static function sanitize(array $data)
    {
        //Si es "", o null, o no está definida no se ejecutará el saneamiento
        if (!empty($data['documentationType'])) $data["documentationType"] = self::sanitizeDocumentationType($data['documentationType']);
        if (!empty($data['documentCode'])) $data["documentCode"] = self::sanitizeDocumentCode($data['documentCode']);
        if (!empty($data['expirationDate'])) $data["expirationDate"] = self::sanitizeExpirationDate($data['expirationDate']);
        if (!empty($data['title'])) $data["title"] = self::sanitizeTitle($data['title']);
        if (!empty($data['firstName'])) $data["firstName"] = self::sanitizeFirstName($data['firstName']);
        if (!empty($data['lastName'])) $data["lastName"] = self::sanitizeLastName($data['lastName']);
        if (!empty($data['emailAddress'])) $data["emailAddress"] = self::sanitizeEmailAddress($data['emailAddress']);
        if (!empty($data['phoneNumber1'])) $data["phoneNumber1"] = self::sanitizePhoneNumber1($data['phoneNumber1']);
        if (!empty($data['phoneNumber2'])) $data["phoneNumber2"] = self::sanitizePhoneNumber2($data['phoneNumber2']);
        if (!empty($data['country'])) $data["country"] = self::sanitizeCountry($data['country']);
        if (!empty($data['townCity'])) $data["townCity"] = self::sanitizeTownCity($data['townCity']);
        if (!empty($data['streetAddress'])) $data["streetAddress"] = self::sanitizeStreetAddress($data['streetAddress']);
        if (!empty($data['zipCode'])) $data["zipCode"] = self::sanitizeZipCode($data['zipCode']);
        if (!empty($data['companyName'])) $data["companyName"] = self::sanitizeCompanyName($data['companyName']);
        if (!empty($data['companyTaxNumber'])) $data["companyTaxNumber"] = self::sanitizeCompanyTaxNumber($data['companyTaxNumber']);
        if (!empty($data['companyPhoneNumber'])) $data["companyPhoneNumber"] = self::sanitizeCompanyPhoneNumber($data['companyPhoneNumber']);
        if (!empty($data['dateBirth'])) $data["dateBirth"] = self::sanitizeDateBirth($data['dateBirth']);

        return $data;
    }
}
