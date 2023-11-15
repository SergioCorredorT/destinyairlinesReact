<?php
class PassengerValidator
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

        require_once './Models/PassengerModel.php';
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
        if (!preg_match('/^[a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/', $firstName)) {
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
        if (!preg_match('/^[a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/', $lastName)) {
            return false;
        }

        return true;
    }

    public static function validateAgeCategory($ageCategory)
    {
        if (empty($ageCategory)) {
            return false;
        }

        require_once './Models/PassengerModel.php';
        $passengerModel = new PassengerModel();

        if (!$passengerModel->isAllowedValue($ageCategory, 'ageCategory')) {
            return false;
        }

        return true;
    }

    public static function validateNationality($nationality)
    {
        // Comprueba si la nacionalidad está vacía
        if (empty($nationality)) {
            return false;
        }

        // Comprueba si la nacionalidad solo contiene letras y espacios
        if (!preg_match('/^[a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/', $nationality)) {
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
        if (!preg_match('/^[a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/', $country)) {
            return false;
        }

        return true;
    }

    public static function validateDateBirth($dateBirth)
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

        return true;
    }

    public static function validateAssistiveDevices($assistiveDevices)
    {
        if (empty($assistiveDevices)) {
            return false;
        }

        require_once './Models/AdditionalInformationModel.php';
        $AdditionalInformationModel = new AdditionalInformationModel();
        if ($AdditionalInformationModel->isAllowedValue($assistiveDevices, 'assistiveDevices')) {
            return true;
        }
        return false;
    }

    public static function validateMedicalEquipment($medicalEquipment)
    {
        if (empty($medicalEquipment)) {
            return false;
        }

        require_once './Models/AdditionalInformationModel.php';
        $AdditionalInformationModel = new AdditionalInformationModel();
        if ($AdditionalInformationModel->isAllowedValue($medicalEquipment, 'medicalEquipment')) {
            return true;
        }
        return false;
    }

    public static function validateMobilityLimitations($mobilityLimitations)
    {
        if (empty($mobilityLimitations)) {
            return false;
        }

        require_once './Models/AdditionalInformationModel.php';
        $AdditionalInformationModel = new AdditionalInformationModel();
        if ($AdditionalInformationModel->isAllowedValue($mobilityLimitations, 'mobilityLimitations')) {
            return true;
        }
        return false;
    }

    public static function validateCommunicationNeeds($communicationNeeds)
    {
        if (empty($communicationNeeds)) {
            return false;
        }

        require_once './Models/AdditionalInformationModel.php';
        $AdditionalInformationModel = new AdditionalInformationModel();
        if ($AdditionalInformationModel->isAllowedValue($communicationNeeds, 'communicationNeeds')) {
            return true;
        }
        return false;
    }

    public static function validateMedicationRequirements($medicationRequirements)
    {
        if (empty($medicationRequirements)) {
            return false;
        }

        require_once './Models/AdditionalInformationModel.php';
        $AdditionalInformationModel = new AdditionalInformationModel();
        if ($AdditionalInformationModel->isAllowedValue($medicationRequirements, 'medicationRequirements')) {
            return true;
        }
        return false;
    }

    public static function validateIndividualServiceCodes($individualServiceCodes)
    {
        if (!is_array($individualServiceCodes)) {
            return false;
        }

        require_once './Models/ServicesModel.php';
        $servicesModel = new ServicesModel();
        //Obtener serviceCode s donde sean individuales y paidService
        $individualServicePaidCodes = $servicesModel->readIndividualServicePaidCodes();

        foreach ($individualServiceCodes as $individualServiceCode) {
            $found = false;
            foreach ($individualServicePaidCodes as $individualServicePaidCode) {
                if ($individualServiceCode === $individualServicePaidCode['serviceCode']) {
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                return false;
            }
        }
        return true;
    }

    public static function validate(array $data)
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

        if (isset($data['ageCategory']) && !self::validateAgeCategory($data['ageCategory'])) {
            return false;
        }
 
        if (isset($data['nationality']) && !self::validateNationality($data['nationality'])) {
            return false;
        }

        if (isset($data['country']) && !self::validateCountry($data['country'])) {
            return false;
        }

        if (isset($data['dateBirth']) && !self::validateDateBirth($data['dateBirth'])) {
            return false;
        }

        if (isset($data['assistiveDevices']) && !self::validateAssistiveDevices($data['assistiveDevices'])) {
            return false;
        }

        if (isset($data['medicalEquipment']) && !self::validateMedicalEquipment($data['medicalEquipment'])) {
            return false;
        }

        if (isset($data['mobilityLimitations']) && !self::validateMobilityLimitations($data['mobilityLimitations'])) {
            return false;
        }

        if (isset($data['communicationNeeds']) && !self::validateCommunicationNeeds($data['communicationNeeds'])) {
            return false;
        }

        if (isset($data['medicationRequirements']) && !self::validateMedicationRequirements($data['medicationRequirements'])) {
            return false;
        }

        if (isset($data['services']) && !self::validateIndividualServiceCodes($data['services'])) {
            return false;
        }

        return true;
    }
}
