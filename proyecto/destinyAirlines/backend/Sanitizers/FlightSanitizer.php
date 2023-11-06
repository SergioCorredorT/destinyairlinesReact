<?php
class FlightSanitizer
{
    public static function sanitizeFlightCode($flightCode)
    {
        return htmlspecialchars(trim($flightCode));
    }
    public static function sanitizePeopleNumber($peopleNumber)
    {
        if (is_numeric($peopleNumber)) {
            return intval($peopleNumber);
        } else {
            return 0;
        }
    }

    public static function sanitize(array $data)
    {
        $arraySanitized = [];
        //Si es "", o null, o no está definida no se ejecutará el saneamiento
        if (!empty($data['flightCode'])) $arraySanitized["flightCode"] = self::sanitizeFlightCode($data['flightCode']);
        if (!empty($data['adultsNumber'])) $arraySanitized["adultsNumber"] = self::sanitizePeopleNumber($data['adultsNumber']);
        if (!empty($data['childsNumber'])) $arraySanitized["childsNumber"] = self::sanitizePeopleNumber($data['childsNumber']);
        if (!empty($data['infantsNumber'])) $arraySanitized["infantsNumber"] = self::sanitizePeopleNumber($data['infantsNumber']);

        return $arraySanitized;
    }
}
