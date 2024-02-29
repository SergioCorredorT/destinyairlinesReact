<?php
class BookServicesValidator
{
    public static function validateCollectiveServiceCodes(array $collectiveServiceCodes): bool
    {
        if (!is_array($collectiveServiceCodes)) {
            return false;
        }

        require_once ROOT_PATH . '/Models/ServicesModel.php';
        $servicesModel = new ServicesModel();
        $collectiveServicePaidCodes = $servicesModel->readCollectiveActiveServicePaidCodes();

        foreach ($collectiveServiceCodes as $keyCollectiveServiceCode => $collectiveServiceCode) {
            $found = false;
            foreach ($collectiveServicePaidCodes as $collectiveServicePaidCode) {
                if ($collectiveServiceCode === $collectiveServicePaidCode['serviceCode']) {
                    if($keyCollectiveServiceCode === $collectiveServiceCode) {
                        $found = true;
                    }
                    break;
                }
            }
            if (!$found) {
                return false;
            }
        }
        return true;
    }


    public static function validate(array $data): bool | array
    {
        if (isset($data) && !self::validateCollectiveServiceCodes($data)) {
            return false;
        }

        return $data;
    }
}
