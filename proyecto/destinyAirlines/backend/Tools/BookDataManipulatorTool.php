<?php
require_once './Tools/SessionTool.php';
require_once './Tools/IniTool.php';
require_once './Models/FlightModel.php';
require_once './Models/ServicesModel.php';

class BookDataManipulatorTool
{
    public function getSeatsNeeded(array $passengers)
    {
        $count = 0;
        foreach ($passengers as $passenger) {
            if ($passenger['ageCategory'] == 'adult' || $passenger['ageCategory'] == 'child') {
                $count++;
            }
        }
        return $count;
    }
    
    public static function generateUUID()
    {
        $uuid4 = Ramsey\Uuid\Uuid::uuid4();
        return $uuid4->toString(); // i.e. 25769c6c-d34d-4bfe-ba98-e0ee856f3e7a
    }

    public static function getPassengersNumberByAgeCategory($passengers)
    {
        $countsAgeCategories = ['adult' => 0, 'child' => 0, 'infant' => 0];

        foreach ($passengers as $passenger) {
            $countsAgeCategories[$passenger['ageCategory']]++;
        }

        return $countsAgeCategories;
    }
}
