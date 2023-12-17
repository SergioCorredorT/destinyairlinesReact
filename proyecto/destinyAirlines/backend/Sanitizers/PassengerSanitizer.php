<?php
class PassengerSanitizer
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
        // Elimina todos los números
        $sanitizedTitle = preg_replace('/[0-9]/', '', $title);
        return htmlspecialchars(trim($sanitizedTitle));
    }


    public static function sanitizeFirstName(string $firstName): string
    {
        $sanitizedTitle = preg_replace('/[0-9]/', '', $firstName);
        return htmlspecialchars(trim($sanitizedTitle));
    }

    public static function sanitizeLastName(string $lastName): string
    {
        // Elimina todos los números
        $sanitizedLastName = preg_replace('/[0-9]/', '', $lastName);
        return htmlspecialchars(trim($sanitizedLastName));
    }

    public static function sanitizeNationality(string $nationality): string
    {
        // Elimina todos los números
        $sanitizedNationality = preg_replace('/[0-9]/', '', $nationality);
        return htmlspecialchars(trim($sanitizedNationality));
    }

    public static function sanitizeCountry(string $country): string
    {
        // Elimina todos los números
        $sanitizedCountry = preg_replace('/[0-9]/', '', $country);
        return htmlspecialchars(trim($sanitizedCountry));
    }

    public static function sanitizeDateBirth(string $dateBirth): string
    {
        // Elimina todos los caracteres que no sean números, guiones o barras
        $sanitizedDateBirth = preg_replace('/[^0-9\-\/]/', '', $dateBirth);
        return $sanitizedDateBirth;
    }

    public static function sanitizeAssistiveDevices(string $assistiveDevices): string
    {
        // Elimina todos los números
        $sanitizedAssistiveDevices = preg_replace('/[0-9]/', '', $assistiveDevices);
        return htmlspecialchars(trim($sanitizedAssistiveDevices));
    }

    public static function sanitizeMedicalEquipment(string $medicalEquipment): string
    {
        // Elimina todos los números
        $sanitizedMedicalEquipment = preg_replace('/[0-9]/', '', $medicalEquipment);
        return htmlspecialchars(trim($sanitizedMedicalEquipment));
    }

    public static function sanitizeMobilityLimitations(string $mobilityLimitations): string
    {
        // Elimina todos los números
        $sanitizedMobilityLimitations = preg_replace('/[0-9]/', '', $mobilityLimitations);
        return htmlspecialchars(trim($sanitizedMobilityLimitations));
    }

    public static function sanitizeCommunicationNeeds(string $communicationNeeds): string
    {
        // Elimina todos los números
        $sanitizedCommunicationNeeds = preg_replace('/[0-9]/', '', $communicationNeeds);
        return htmlspecialchars(trim($sanitizedCommunicationNeeds));
    }

    public static function sanitizeMedicationRequirements(string $medicationRequirements): string
    {
        // Elimina todos los números
        $sanitizedMedicationRequirements = preg_replace('/[0-9]/', '', $medicationRequirements);
        return htmlspecialchars(trim($sanitizedMedicationRequirements));
    }

    public static function sanitizeIndividualServiceCodes(string $individualServiceCodes): string
    {
        if (is_array($individualServiceCodes)) {
            return array_map('trim', array_map('htmlspecialchars', $individualServiceCodes));
        }
        return $individualServiceCodes;
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
        if (!empty($data['nationality'])) $data["nationality"] = self::sanitizeNationality($data['nationality']);
        if (!empty($data['country'])) $data["country"] = self::sanitizeCountry($data['country']);
        if (!empty($data['dateBirth'])) $data["dateBirth"] = self::sanitizeDateBirth($data['dateBirth']);
        if (!empty($data['assistiveDevices'])) $data["assistiveDevices"] = self::sanitizeAssistiveDevices($data['assistiveDevices']);
        if (!empty($data['medicalEquipment'])) $data["medicalEquipment"] = self::sanitizeMedicalEquipment($data['medicalEquipment']);
        if (!empty($data['mobilityLimitations'])) $data["mobilityLimitations"] = self::sanitizeMobilityLimitations($data['mobilityLimitations']);
        if (!empty($data['communicationNeeds'])) $data["communicationNeeds"] = self::sanitizeCommunicationNeeds($data['communicationNeeds']);
        if (!empty($data['medicationRequirements'])) $data["medicationRequirements"] = self::sanitizeMedicationRequirements($data['medicationRequirements']);
        if (!empty($data['services'])) $data["services"] = self::sanitizeIndividualServiceCodes($data['services']);

        return $data;
    }
}
