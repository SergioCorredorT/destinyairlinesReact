<?php
class PassengersValidator
{
    public static function validateUniqueDocTypeAndDocCode($data)
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

    public static function validateAdultNumber($data)
    {
        foreach ($data as $passenger) {
            if ($passenger['ageCategory'] === 'adult') {
                return true;
            }
        }
        return false;
    }

    public static function validate(array $data)
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

        return true;
    }
}
