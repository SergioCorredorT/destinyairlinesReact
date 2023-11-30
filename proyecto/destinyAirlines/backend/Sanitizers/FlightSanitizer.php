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

    public static function sanitizeDirection($direction)
    {
        return htmlspecialchars(trim($direction));
    }

    public static function sanitize(array $data)
    {
        //Si es "", o null, o no está definida no se ejecutará el saneamiento
        if (!empty($data['flightCode'])) $data["flightCode"] = self::sanitizeFlightCode($data['flightCode']);
        if (!empty($data['adultsNumber'])) $data["adultsNumber"] = self::sanitizePeopleNumber($data['adultsNumber']);
        if (!empty($data['childsNumber'])) $data["childsNumber"] = self::sanitizePeopleNumber($data['childsNumber']);
        if (!empty($data['infantsNumber'])) $data["infantsNumber"] = self::sanitizePeopleNumber($data['infantsNumber']);
        if (!empty($data['direction'])) $data["direction"] = self::sanitizeDirection($data['direction']);

        return $data;
    }
}
