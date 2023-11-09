<?php
class PrimaryContactDetailsValidator
{
    public static function validateDocumentation($documentationType, $documentCode)
    {
        if (!self::validateDocumentationType($documentationType)) {
            return false;
        }

        if (!self::validateDocumentCode($documentCode, $documentationType)) {
            return false;
        }

        return true;
    }

    public static function validateDocumentationType($documentationType)
    {
        if (empty($documentationType)) {
            return false;
        }

        require_once './Models/PassengerModel';
        $passengerModel = new PassengerModel();
        if (!$passengerModel->isAllowedValue($documentationType, 'documentationType')) {
            return false;
        }
        return true;
    }

    public static function validateDocumentCode($documentCode, $documentationType)
    {
        switch (strtolower($documentationType)) {
            case 'dni': {
                    // DNI: 8 dígitos seguidos de una letra mayúscula
                    if (!preg_match('/^[0-9]{8}[A-Z]$/', $documentCode)) {
                        return false;
                    }
                    break;
                }
            case 'passport': {
                    // Pasaporte: 2 letras mayúsculas seguidas de 6 dígitos
                    if (!preg_match('/^[A-Z]{2}[0-9]{6}$/', $documentCode)) {
                        return false;
                    }
                    break;
                }
            case 'drivers_license': {
                    // Licencia de conducir: 1 letra mayúscula seguida de 7 dígitos
                    if (!preg_match('/^[A-Z]{1}[0-9]{7}$/', $documentCode)) {
                        return false;
                    }
                    break;
                }
            case 'residence_card_or_work_permit': {
                    // Tarjeta de residencia o permiso de trabajo: 3 letras mayúsculas seguidas de 9 dígitos
                    if (!preg_match('/^[A-Z]{3}[0-9]{9}$/', $documentCode)) {
                        return false;
                    }
                    break;
                }
        }
        return true;
    }

    public static function validateExpirationDate($expirationDate)
    {
        // Comprueba si la fecha de expiración está en el formato correcto (MM/AA)
        if (!preg_match('/^(0[1-9]|1[0-2])\/[0-9]{2}$/', $expirationDate)) {
            return false;
        }

        // Comprueba si la fecha de expiración es una fecha futura
        $currentDate = date('m/y');
        $exp = strtotime($expirationDate);
        $current = strtotime($currentDate);
        if ($current > $exp) {
            return false;
        }

        return true;
    }

    public static function validateTitle($title)
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

    public static function validateFirstName($firstName)
    {
        // Comprueba si el nombre está vacío
        if (empty($firstName)) {
            return false;
        }

        // Comprueba si el nombre solo contiene letras y espacios
        if (!preg_match('/^[a-zA-Z ]+$/', $firstName)) {
            return false;
        }

        return true;
    }

    public static function validateLastName($lastName)
    {
        // Comprueba si el apellido está vacío
        if (empty($lastName)) {
            return false;
        }

        // Comprueba si el apellido solo contiene letras y espacios
        if (!preg_match('/^[a-zA-Z ]+$/', $lastName)) {
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

    public static function validateCountry($country)
    {
        // Comprueba si el país está vacío
        if (empty($country)) {
            return false;
        }

        // Comprueba si el país solo contiene letras y espacios
        if (!preg_match('/^[a-zA-Z ]+$/', $country)) {
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

        return true;
    }
}
