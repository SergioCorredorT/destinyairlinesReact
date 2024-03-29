<?php
require_once ROOT_PATH . '/DataProcessing/Sanitizers/TokenSanitizer.php';
class UserSanitizer
{

    public static function sanitizeDocumentationType(string $documentationType): string
    {
        return htmlspecialchars(trim($documentationType));
    }

    public static function sanitizeDocumentCode(string $documentCode): string
    {
        // Aquí va tu código de saneamiento para 'documentCode'
        return htmlspecialchars(trim($documentCode));
    }

    public static function sanitizeExpirationDate(string $expirationDate): string
    {
        // Elimina todos los caracteres que no sean números, guiones o barras
        $sanitizedExpirationDate = preg_replace('/[^0-9\-\/]/', '', $expirationDate);
        return $sanitizedExpirationDate;
    }

    public static function sanitizeTitle(string $title): string
    {
        return htmlspecialchars(trim($title));
    }

    public static function sanitizeFirstName(string $firstName): string
    {
        return htmlspecialchars(trim($firstName));
    }
    public static function sanitizeLastName(string $lastName): string
    {
        return htmlspecialchars(trim($lastName));
    }
    public static function sanitizeTownCity(string $townCity): string
    {
        return htmlspecialchars(trim($townCity));
    }
    public static function sanitizeStreetAddress(string $streetAddress): string
    {
        return htmlspecialchars(trim($streetAddress));
    }
    public static function sanitizeZipCode(string $zipCode): string
    {
        return htmlspecialchars(trim($zipCode));
    }
    public static function sanitizeCountry(string $country): string
    {
        return htmlspecialchars(trim($country));
    }
    public static function sanitizeEmailAddress(string $emailAddress): string
    {
        return trim($emailAddress);
    }
    public static function sanitizePassword(string $password): string
    {
        return $password;
    }
    public static function sanitizePhoneNumber1(string $phoneNumber1): string
    {
        return htmlspecialchars(trim($phoneNumber1));
    }
    public static function sanitizePhoneNumber2(string $phoneNumber2): string
    {
        return htmlspecialchars(trim($phoneNumber2));
    }
    public static function sanitizePhoneNumber3(string $phoneNumber3): string
    {
        return htmlspecialchars(trim($phoneNumber3));
    }
    public static function sanitizeCompanyName(string $companyName): string
    {
        return htmlspecialchars(trim($companyName));
    }
    public static function sanitizeCompanyTaxNumber(string $companyTaxNumber): string
    {
        return htmlspecialchars(trim($companyTaxNumber));
    }
    public static function sanitizeCompanyPhoneNumber(string $companyPhoneNumber): string
    {
        return htmlspecialchars(trim($companyPhoneNumber));
    }

    public static function sanitizeDateBirth(string $dateBirth): string
    {
        // Elimina todos los caracteres que no sean números, guiones o barras
        $sanitizedDateBirth = preg_replace('/[^0-9\-\/]/', '', $dateBirth);
        return $sanitizedDateBirth;
    }

    public static function sanitize(array $data): array
    {
        //Si es "", o null, o no está definida no se ejecutará el saneamiento
        if (!empty($data['documentationType'])) $data["documentationType"] = self::sanitizeDocumentationType($data['documentationType']);
        if (!empty($data['documentCode'])) $data["documentCode"] = self::sanitizeDocumentCode($data['documentCode']);
        if (!empty($data['expirationDate'])) $data["expirationDate"] = self::sanitizeExpirationDate($data['expirationDate']);
        if (!empty($data['title'])) $data["title"] = self::sanitizeTitle($data['title']);
        if (!empty($data['firstName'])) $data["firstName"] = self::sanitizeFirstName($data['firstName']);
        if (!empty($data['lastName'])) $data["lastName"] = self::sanitizeLastName($data['lastName']);
        if (!empty($data['townCity'])) $data["townCity"] = self::sanitizeTownCity($data['townCity']);
        if (!empty($data['streetAddress'])) $data["streetAddress"] = self::sanitizeStreetAddress($data['streetAddress']);
        if (!empty($data['zipCode'])) $data["zipCode"] = self::sanitizeZipCode($data['zipCode']);
        if (!empty($data['country'])) $data["country"] = self::sanitizeCountry($data['country']);
        if (!empty($data['password'])) $data["password"] = self::sanitizePassword($data['password']);
        if (!empty($data['oldPassword'])) $data["oldPassword"] = self::sanitizePassword($data['oldPassword']);
        if (!empty($data['phoneNumber1'])) $data["phoneNumber1"] = self::sanitizePhoneNumber1($data['phoneNumber1']);
        if (!empty($data['phoneNumber2'])) $data["phoneNumber2"] = self::sanitizePhoneNumber2($data['phoneNumber2']);
        if (!empty($data['phoneNumber3'])) $data["phoneNumber3"] = self::sanitizePhoneNumber3($data['phoneNumber3']);
        if (!empty($data['companyName'])) $data["companyName"] = self::sanitizeCompanyName($data['companyName']);
        if (!empty($data['companyTaxNumber'])) $data["companyTaxNumber"] = self::sanitizeCompanyTaxNumber($data['companyTaxNumber']);
        if (!empty($data['companyPhoneNumber'])) $data["companyPhoneNumber"] = self::sanitizeCompanyPhoneNumber($data['companyPhoneNumber']);
        if (!empty($data['emailAddress'])) $data["emailAddress"] = self::sanitizeEmailAddress($data['emailAddress']);
        if (!empty($data['emailAddressAuth'])) $data["emailAddressAuth"] = self::sanitizeEmailAddress($data['emailAddressAuth']);
        if (!empty($data['dateBirth'])) $data["dateBirth"] = self::sanitizeDateBirth($data['dateBirth']);
        if (!empty($data['accessToken'])) $data["accessToken"] = TokenSanitizer::sanitizeToken($data['accessToken']);
        if (!empty($data['refreshToken'])) $data["refreshToken"] = TokenSanitizer::sanitizeToken($data['refreshToken']);

        return $data;
    }
}
