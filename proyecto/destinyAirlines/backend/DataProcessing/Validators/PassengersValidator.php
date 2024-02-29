<?php
class PassengersValidator
{
    public static function validateUniqueDocTypeAndDocCode(array $data): bool
    {
        $uniqueCheck = array();
        foreach ($data as $passenger) {
            $docType = $passenger['documentationType'];
            $docCode = $passenger['documentCode'];
            $key = $docType . '-' . $docCode;
            if (isset($uniqueCheck[$key])) {
                return false;
            } else {
                $uniqueCheck[$key] = true;
            }
        }
        return true;
    }

    public static function validateAdultNumber(array $data): bool
    {
        foreach ($data as $passenger) {
            if ($passenger['ageCategory'] === 'adult') {
                return true;
            }
        }
        return false;
    }

    public static function validateMaxNumberOfPassengersPerAgeCategory(array $data): bool
    {
        require_once ROOT_PATH . '/Tools/IniTool.php';
        $iniTool = new IniTool(ROOT_PATH  . '/Config/cfg.ini');
        $bookSettings = $iniTool->getKeysAndValues('bookSettings');
        $maxNumberOfPassengersPerAgeCategory = intval($bookSettings['maxNumberOfPassengersPerAgeCategory']);
        $countsAgeCategories = ['adult' => 0, 'child' => 0, 'infant' => 0];

        foreach ($data as $passenger) {
            $countsAgeCategories[$passenger['ageCategory']]++;
        }

        foreach ($countsAgeCategories as $count) {
            if ($count > $maxNumberOfPassengersPerAgeCategory) {
                return false;
            }
        }

        return true;
    }

    public static function validate(array $data): bool | array
    {
        if (count($data) < 1) {
            return false;
        }

        if (!self::validateAdultNumber($data)) {
            return false;
        }

        if (!self::validateUniqueDocTypeAndDocCode($data)) {
            return false;
        }

        if (!self::validateMaxNumberOfPassengersPerAgeCategory($data)) {
            return false;
        }

        return $data;
    }
}
