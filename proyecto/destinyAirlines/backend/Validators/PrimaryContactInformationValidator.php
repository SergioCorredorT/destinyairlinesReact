<?php
class PrimaryContactInformationValidator
{
    public static function validateDocumentation(string $docType, string $docCode): bool
    {
        require_once ROOT_PATH . '/Validators/DocumentTypeValidator.php';
        if(!DocumentTypeValidator::validateDocumentType($docType, $docCode)) {
            return false;
        }

        return true;
    }

    public static function validateExpirationDate(string $expirationDate): bool
    {
        // Comprueba si la fecha de expiración está en el formato correcto (YYYY-MM-DD)
        if (!preg_match('/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[12][0-9]|3[01])$/', $expirationDate)) {
            return false;
        }
    
        // Comprueba si la fecha de expiración es una fecha futura
        $currentDate = date('Y-m-d');
        $exp = strtotime($expirationDate);
        $current = strtotime($currentDate);
        if ($current > $exp) {
            return false;
        }
    
        return true;
    }

    public static function validateTitle(string $title): bool
    {
        // Comprueba si el título está vacío
        if (empty($title)) {
            return false;
        }

        // Comprueba si el título tiene el formato correcto
        // (solo letras, números, espacios y guiones)
        if (!preg_match('/^[a-zA-Z0-9 -]+$/', $title)) {
            return false;
        }

        return true;
    }

    public static function validateFirstName(string $firstName): bool
    {
        // Comprueba si el nombre está vacío
        if (empty($firstName)) {
            return false;
        }

        // Comprueba si el nombre solo contiene letras y espacios
        if (!preg_match('/^[a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/', $firstName)) {
            return false;
        }

        return true;
    }

    public static function validateLastName(string $lastName): bool
    {
        // Comprueba si el apellido está vacío
        if (empty($lastName)) {
            return false;
        }

        // Comprueba si el apellido solo contiene letras y espacios
        if (!preg_match('/^[a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/', $lastName)) {
            return false;
        }

        return true;
    }

    public static function validateEmailAddress(string $emailAddress): bool
    {
        if (strlen($emailAddress) < 7 || !filter_var($emailAddress, FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        return true;
    }

    public static function validatePhoneNumber1(string $phoneNumber1): bool
    {
        if ($phoneNumber1 != "" && !preg_match("/^(\+?[0-9]{9,})$/", $phoneNumber1)) {
            return false;
        }
        return true;
    }

    public static function validatePhoneNumber2(string $phoneNumber1): bool
    {
        if ($phoneNumber1 != "" && !preg_match("/^(\+?[0-9]{9,})$/", $phoneNumber1)) {
            return false;
        }
        return true;
    }

    public static function validateCountry(string $country): bool
    {
        // Comprueba si el país está vacío
        if (empty($country)) {
            return false;
        }

        // Comprueba si el país solo contiene letras y espacios
        if (!preg_match('/^[a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/', $country)) {
            return false;
        }

        return true;
    }

    public static function validateTownCity(string $townCity): bool
    {
        if ($townCity != "" && strlen($townCity) < 2) {
            return false;
        }
        return true;
    }

    public static function validateStreetAddress(string $streetAddress): bool
    {
        if ($streetAddress != "" && strlen($streetAddress) < 2) {
            return false;
        }
        return true;
    }

    public static function validateZipCode(string $zipCode): bool
    {
        if ($zipCode != "" && strlen($zipCode) < 2) {
            return false;
        }
        return true;
    }

    public static function validateCompanyName(string $companyName): bool
    {
        if ($companyName != "" && strlen($companyName) < 2) {
            return false;
        }
        return true;
    }

    public static function validateCompanyTaxNumber(string $companyTaxNumber): bool
    {
        if ($companyTaxNumber != "" && strlen($companyTaxNumber) < 2) {
            return false;
        }
        return true;
    }

    public static function validateCompanyPhoneNumber(string $companyPhoneNumber): bool
    {
        if ($companyPhoneNumber != "" && !preg_match("/^(\+?[0-9]{9,})$/", $companyPhoneNumber)) {
            return false;
        }
        return true;
    }

    public static function validateDateBirth(string $dateBirth): bool
    {
        // Comprueba si la fecha de nacimiento está vacía
        if (empty($dateBirth)) {
            return false;
        }
        // Comprueba si la fecha de nacimiento tiene el formato correcto (DD-MM-YYYY)
        if (!preg_match('/^([0-9]{4})-(0[1-9]|1[012])-(0[1-9]|[12][0-9]|3[01])$/', $dateBirth)) {
            return false;
        }

        // Comprueba si la fecha de nacimiento es una fecha válida
        list($year, $month, $day) = explode('-', $dateBirth);
        if (!checkdate($month, $day, $year)) {
            return false;
        }

        $currentDate = date('Y-m-d');
        if ($dateBirth > $currentDate) {
            return false;
        }

        require_once ROOT_PATH . '/Tools/TimeTool.php';
        require_once ROOT_PATH . '/Tools/IniTool.php';
        $iniTool = new IniTool(ROOT_PATH  . '/Config/cfg.ini');
        $timeTool = new TimeTool();
        $primaryContactInformationSettings = $iniTool->getKeysAndValues('primaryContactInformationSettings');
        if($timeTool->getAge($dateBirth) < intval($primaryContactInformationSettings['minAge'])){
            return false;
        }

        return true;
    }

    public static function validate(array $data): bool
    {
        if (isset($data['documentationType']) && isset($data['documentCode']) && !self::validateDocumentation($data['documentationType'], $data['documentCode'])) {
            return false;
        }

        if (isset($data['title']) && !self::validateTitle($data['title'])) {
            return false;
        }

        if (isset($data['firstName']) && !self::validateFirstName($data['firstName'])) {
            return false;
        }

        if (isset($data['lastName']) && !self::validateLastName($data['lastName'])) {
            return false;
        }

        if (isset($data['emailAddress']) && !self::validateEmailAddress($data['emailAddress'])) {
            return false;
        }

        if (isset($data['phoneNumber1']) && !self::validatePhoneNumber1($data['phoneNumber1'])) {
            return false;
        }
        if (isset($data['phoneNumber2']) && !self::validatePhoneNumber2($data['phoneNumber2'])) {
            return false;
        }

        if (isset($data['country']) && !self::validateCountry($data['country'])) {
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

        if (isset($data['companyName']) && !self::validateCompanyName($data['companyName'])) {
            return false;
        }
        if (isset($data['companyTaxNumber']) && !self::validateCompanyTaxNumber($data['companyTaxNumber'])) {
            return false;
        }
        if (isset($data['companyPhoneNumber']) && !self::validateCompanyPhoneNumber($data['companyPhoneNumber'])) {
            return false;
        }

        if (isset($data['dateBirth']) && !self::validateDateBirth($data['dateBirth'])) {
            return false;
        }

        if (isset($data['expirationDate']) && !self::validateExpirationDate($data['expirationDate'])) {
            return false;
        }

        return true;
    }
}
