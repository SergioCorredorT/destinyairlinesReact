<?php
class FlightValidator
{
    public static function validateFlightCode($flightCode)
    {
        if ($flightCode != "" && strlen($flightCode) < 3) {
            return false;
        }
        return true;
    }

    public static function validatePeopleNumber($adultsNumber, $childsNumber, $infantsNumber)
    {
        if (!self::validateAdultsNumber($adultsNumber)) {
            return false;
        }
        if (!self::validateChildsNumber($childsNumber)) {
            return false;
        }
        if (!self::validateInfantsNumber($infantsNumber)) {
            return false;
        }
    }

    public static function validateAdultsNumber($adultsNumber)
    {
        //Mínimo un adulto
        if ($adultsNumber > 999 || $adultsNumber <= 0) {
            return false;
        }
        return true;
    }

    public static function validateChildsNumber($childsNumber)
    {
        if ($childsNumber > 999) {
            return false;
        }
        return true;
    }

    public static function validateInfantsNumber($infantsNumber)
    {
        if ($infantsNumber > 999) {
            return false;
        }
        return true;
    }

    public static function validate($data)
    {
        $isValid = true;

        if (isset($data['flightCode']) && !self::validateFlightCode($data['flightCode'])) {
            $isValid = false;
        }

        if (isset($data['adultsNumber']) && !self::validateAdultsNumber($data['adultsNumber'])) {
            return false;
        }

        if (isset($data['childsNumber']) && !self::validateChildsNumber($data['childsNumber'])) {
            return false;
        }

        if (isset($data['infantsNumber']) && !self::validateInfantsNumber($data['infantsNumber'])) {
            return false;
        }

        return $isValid;
    }
}
