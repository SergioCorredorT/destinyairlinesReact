<?php
class TimeTool
{
    public static function getHoursDifference($date, $hour)
    {
        $dateTime = $date . ' ' . $hour;
        $dateTime = DateTime::createFromFormat('Y-m-d H:i:s', $dateTime);
        $currentDateTime = new DateTime();
        $interval = $currentDateTime->diff($dateTime);
        $hoursDifference = $interval->days * 24 + $interval->h;
        return $hoursDifference;
    }

    function isPastDateTime($date, $time)
    {
        $dateTime = $date . ' ' . $time;
        $providedDateTime = DateTime::createFromFormat('Y-m-d H:i:s', $dateTime);
        $currentDateTime = new DateTime();
        if ($providedDateTime < $currentDateTime) {
            return true;
        }
        return false;
    }

    function calculateAge($birthDate)
    {
        $birthDate = new DateTime($birthDate);
        $currentDate = new DateTime();
        $age = $currentDate->diff($birthDate);
        return $age->y;
    }
}
