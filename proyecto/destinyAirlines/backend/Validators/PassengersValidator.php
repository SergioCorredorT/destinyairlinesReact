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

    public static function validate(array $data)
    {
        if (!self::validateUniqueDocTypeAndDocCode($data)) {
            return false;
        }

        return true;
    }
}
