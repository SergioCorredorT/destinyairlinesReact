<?php
class PassengerValidator
{
    public static function validateDocumentation(string $docType, string $docCode): bool
    {
        require_once ROOT_PATH . '/DataProcessing/ProcessData.php';
        $processData = new ProcessData();
        $document = $processData->processData(['document'=>['docType'=>$docType, 'docCode'=>$docCode]], 'DocumentType');
        if (!$document) {
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

    public static function validateNationality(string $nationality): bool
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

        return true;
    }

    public static function validateAssistiveDevices(string $assistiveDevices): bool
    {
        if (empty($assistiveDevices)) {
            return false;
        }

        require_once ROOT_PATH . '/Tools/IniTool.php';
        $iniTool = new IniTool(ROOT_PATH  . '/Config/cfg.ini');
        $additional_informations = $iniTool->getKeysAndValues('additional_informations');
        $assistiveDevicesValues = $additional_informations['assistiveDevices'];

        if (in_array($assistiveDevices, $assistiveDevicesValues)) {
            return true;
        }
        return false;
    }

    public static function validateMedicalEquipment(string $medicalEquipment): bool
    {
        if (empty($medicalEquipment)) {
            return false;
        }

        require_once ROOT_PATH . '/Tools/IniTool.php';
        $iniTool = new IniTool(ROOT_PATH  . '/Config/cfg.ini');
        $additional_informations = $iniTool->getKeysAndValues('additional_informations');
        $medicalEquipmentValues = $additional_informations['medicalEquipment'];

        if (in_array($medicalEquipment, $medicalEquipmentValues)) {
            return true;
        }
        return false;
    }

    public static function validateMobilityLimitations(string $mobilityLimitations): bool
    {
        if (empty($mobilityLimitations)) {
            return false;
        }

        require_once ROOT_PATH . '/Tools/IniTool.php';
        $iniTool = new IniTool(ROOT_PATH  . '/Config/cfg.ini');
        $additional_informations = $iniTool->getKeysAndValues('additional_informations');
        $mobilityLimitationsValues = $additional_informations['mobilityLimitations'];

        if (in_array($mobilityLimitations, $mobilityLimitationsValues)) {
            return true;
        }
        return false;
    }

    public static function validateCommunicationNeeds(string $communicationNeeds): bool
    {
        if (empty($communicationNeeds)) {
            return false;
        }

        require_once ROOT_PATH . '/Tools/IniTool.php';
        $iniTool = new IniTool(ROOT_PATH  . '/Config/cfg.ini');
        $additional_informations = $iniTool->getKeysAndValues('additional_informations');
        $communicationNeedsValues = $additional_informations['communicationNeeds'];

        if (in_array($communicationNeeds, $communicationNeedsValues)) {
            return true;
        }
        return false;
    }

    public static function validateMedicationRequirements(string $medicationRequirements): bool
    {
        if (empty($medicationRequirements)) {
            return false;
        }

        require_once ROOT_PATH . '/Tools/IniTool.php';
        $iniTool = new IniTool(ROOT_PATH  . '/Config/cfg.ini');
        $additional_informations = $iniTool->getKeysAndValues('additional_informations');
        $medicationRequirementsValues = $additional_informations['medicationRequirements'];

        if (in_array($medicationRequirements, $medicationRequirementsValues)) {
            return true;
        }
        return false;
    }

    public static function validateIndividualServiceCodes(array $individualServiceCodes): bool
    {
        if (!is_array($individualServiceCodes)) {
            return false;
        }

        require_once ROOT_PATH . '/Models/ServicesModel.php';
        $servicesModel = new ServicesModel();
        //Obtener serviceCode s donde sean individuales y paidService
        $individualServicePaidCodes = $servicesModel->readIndividualActiveServicePaidCodes();

        //Para buscar en la key
        foreach ($individualServiceCodes as $individualServiceCode => $value) {
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

    public static function validate(array $data): bool | array
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

        if (isset($data['expirationDate']) && !self::validateExpirationDate($data['expirationDate'])) {
            return false;
        }

        return $data;
    }
}
