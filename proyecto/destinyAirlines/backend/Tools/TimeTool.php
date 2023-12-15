<?php
class TimeTool
{
    public static function getHoursDifference(string $date, string $hour)
    {
        $dateTime = $date . ' ' . $hour;
        $dateTime = DateTime::createFromFormat('Y-m-d H:i:s', $dateTime);
        $currentDateTime = new DateTime();
        $interval = $currentDateTime->diff($dateTime);
        $hoursDifference = $interval->days * 24 + $interval->h;
        return $hoursDifference;
    }

    public function getYearsDifference(string $date)
    {
        $date = new DateTime($date);
        $currentDate = new DateTime();
        $age = $currentDate->diff($date);
        return $age->y;
    }

    public function isPastDateTime(string $date, string $time)
    {
        $dateTime = $date . ' ' . $time;
        $providedDateTime = DateTime::createFromFormat('Y-m-d H:i:s', $dateTime);
        $currentDateTime = new DateTime();
        if ($providedDateTime < $currentDateTime) {
            return true;
        }
        return false;
    }

    public function getAge(string $birthDate)
    {
        $birthDate = new DateTime($birthDate);
        if (!$this->isPastDateTime($birthDate->format('Y-m-d'), $birthDate->format('H:i:s'))) {
            // If the birth date is in the future, return an error message or handle this case as you see fit
            return false;
        }
        // Use the getYearsDifference function to calculate the age
        return $this->getYearsDifference($birthDate->format('Y-m-d'));
    }

    public function getAgeCategory(string $birthDate)
    {
        require_once './Tools/IniTool.php';
        $iniTool = new IniTool('./Config/cfg.ini');
        $ageCategories = $iniTool->getKeysAndValues('ageCategories');
        $age = $this->getAge($birthDate);
        if (!$age) {
            return false;
        }
        foreach ($ageCategories as $ageCategory => $range) {
            [$min, $max] = explode(',', $range);
            if ($age >= $min && $age <= $max) {
                return $ageCategory;
            }
        }
        return false;
    }
}
