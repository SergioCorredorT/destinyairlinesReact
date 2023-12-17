<?php
class FlightValidator
{
    public static function validateFlightCode(string $flightCode): bool
    {
        if ($flightCode != "" && strlen($flightCode) < 3) {
            return false;
        }

        require_once ROOT_PATH . '/Models/FlightModel.php';
        $FlightModel = new FlightModel();
        if($FlightModel->isValidFlight($flightCode)) {
            return true;
        }
        return false;
    }

    public static function validatePeopleNumber(int $adultsNumber, int $childsNumber, int $infantsNumber): bool
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
        return true;
    }

    public static function validateAdultsNumber(int $adultsNumber): bool
    {
        //Mínimo un adulto
        if ($adultsNumber > 999 || $adultsNumber <= 0) {
            return false;
        }
        return true;
    }

    public static function validateChildsNumber(int $childsNumber): bool
    {
        if ($childsNumber > 999 || $childsNumber < 0) {
            return false;
        }
        return true;
    }

    public static function validateInfantsNumber(int $infantsNumber): bool
    {
        if ($infantsNumber > 999  || $infantsNumber < 0) {
            return false;
        }
        return true;
    }

    public static function validateDirection(string $direction): bool
    {
        if ($direction !== 'departure' && $direction !== 'return') {
            return false;
        }
        return true;
    }

    public static function validate(array $data): bool
    {
        if (isset($data['flightCode']) && !self::validateFlightCode($data['flightCode'])) {
            return false;
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

        if (isset($data['direction']) && !self::validateDirection($data['direction'])) {
            return false;
        }

        return true;
    }
}
