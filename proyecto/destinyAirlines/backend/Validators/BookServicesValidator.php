<?php
class BookServicesValidator
{
    public static function validateCollectiveServiceCodes($collectiveServiceCodes)
    {
        if (!is_array($collectiveServiceCodes)) {
            return false;
        }

        require_once './Models/ServicesModel.php';
        $servicesModel = new ServicesModel();
        $collectiveServicePaidCodes = $servicesModel->readCollectiveServicePaidCodes();

        foreach ($collectiveServiceCodes as $collectiveServiceCode) {
            $found = false;
            foreach ($collectiveServicePaidCodes as $collectiveServicePaidCode) {
                if ($collectiveServiceCode === $collectiveServicePaidCode['serviceCode']) {
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                return false;
            }
        }
        return true;
    }

    public static function validateDirection($direction)
    {
        if ($direction !== 'departure' && $direction !== 'return') {
            return false;
        }
        return true;
    }

    public static function validate($data)
    {
        if (isset($data['services']) && !self::validateCollectiveServiceCodes($data['services'])) {
            return false;
        }

        if (isset($data['direction']) && !self::validateDirection($data['direction'])) {
            return false;
        }

        return true;
    }
}
