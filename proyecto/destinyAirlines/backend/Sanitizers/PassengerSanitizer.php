<?php
class PassengerSanitizer
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

    public static function sanitizeAgeCategory($ageCategory)
    {
        // Elimina todos los números
        $sanitizedAgeCategory = preg_replace('/[0-9]/', '', $ageCategory);
        return htmlspecialchars(trim($sanitizedAgeCategory));
    }

    public static function sanitizeNationality($nationality)
    {
        // Elimina todos los números
        $sanitizedNationality = preg_replace('/[0-9]/', '', $nationality);
        return htmlspecialchars(trim($sanitizedNationality));
    }

    public static function sanitizeCountry($country)
    {
        // Elimina todos los números
        $sanitizedCountry = preg_replace('/[0-9]/', '', $country);
        return htmlspecialchars(trim($sanitizedCountry));
    }

    public static function sanitizeDateBirth($dateBirth)
    {
        // Elimina todos los caracteres que no sean números, guiones o barras
        $sanitizedDateBirth = preg_replace('/[^0-9\-\/]/', '', $dateBirth);
        return $sanitizedDateBirth;
    }

    public static function sanitizeAssistiveDevices($assistiveDevices)
    {
        // Elimina todos los números
        $sanitizedAssistiveDevices = preg_replace('/[0-9]/', '', $assistiveDevices);
        return htmlspecialchars(trim($sanitizedAssistiveDevices));
    }

    public static function sanitizeMedicalEquipment($medicalEquipment)
    {
        // Elimina todos los números
        $sanitizedMedicalEquipment = preg_replace('/[0-9]/', '', $medicalEquipment);
        return htmlspecialchars(trim($sanitizedMedicalEquipment));
    }

    public static function sanitizeMobilityLimitations($mobilityLimitations)
    {
        // Elimina todos los números
        $sanitizedMobilityLimitations = preg_replace('/[0-9]/', '', $mobilityLimitations);
        return htmlspecialchars(trim($sanitizedMobilityLimitations));
    }

    public static function sanitizeCommunicationNeeds($communicationNeeds)
    {
        // Elimina todos los números
        $sanitizedCommunicationNeeds = preg_replace('/[0-9]/', '', $communicationNeeds);
        return htmlspecialchars(trim($sanitizedCommunicationNeeds));
    }

    public static function sanitizeMedicationRequirements($medicationRequirements)
    {
        // Elimina todos los números
        $sanitizedMedicationRequirements = preg_replace('/[0-9]/', '', $medicationRequirements);
        return htmlspecialchars(trim($sanitizedMedicationRequirements));
    }


    public static function sanitize(array $data)
    {
        $arraySanitized = [];
        //Si es "", o null, o no está definida no se ejecutará el saneamiento
        if (!empty($data['documentationType'])) $arraySanitized["documentationType"] = self::sanitizeDocumentationType($data['documentationType']);
        if (!empty($data['documentCode'])) $arraySanitized["documentCode"] = self::sanitizeDocumentCode($data['documentCode']);
        if (!empty($data['expirationDate'])) $arraySanitized["expirationDate"] = self::sanitizeExpirationDate($data['expirationDate']);
        if (!empty($data['title'])) $arraySanitized["title"] = self::sanitizeTitle($data['title']);
        if (!empty($data['firstName'])) $arraySanitized["firstName"] = self::sanitizeFirstName($data['firstName']);
        if (!empty($data['lastName'])) $arraySanitized["lastName"] = self::sanitizeLastName($data['lastName']);
        if (!empty($data['ageCategory'])) $arraySanitized["ageCategory"] = self::sanitizeAgeCategory($data['ageCategory']);
        if (!empty($data['nationality'])) $arraySanitized["nationality"] = self::sanitizeNationality($data['nationality']);
        if (!empty($data['country'])) $arraySanitized["country"] = self::sanitizeCountry($data['country']);
        if (!empty($data['dateBirth'])) $arraySanitized["dateBirth"] = self::sanitizeDateBirth($data['dateBirth']);
        if (!empty($data['assistiveDevices'])) $arraySanitized["assistiveDevices"] = self::sanitizeAssistiveDevices($data['assistiveDevices']);
        if (!empty($data['medicalEquipment'])) $arraySanitized["medicalEquipment"] = self::sanitizeMedicalEquipment($data['medicalEquipment']);
        if (!empty($data['mobilityLimitations'])) $arraySanitized["mobilityLimitations"] = self::sanitizeMobilityLimitations($data['mobilityLimitations']);
        if (!empty($data['communicationNeeds'])) $arraySanitized["communicationNeeds"] = self::sanitizeCommunicationNeeds($data['communicationNeeds']);
        if (!empty($data['medicationRequirements'])) $arraySanitized["medicationRequirements"] = self::sanitizeMedicationRequirements($data['medicationRequirements']);

        return $arraySanitized;
    }
}
