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
    
}
