<?php
require_once "./Models/BaseModel.php";
final class ServicesModel extends BaseModel
{
    private const table = "SERVICES";

    public function __construct()
    {
        parent::__construct(self::table);
    }

    public function readSubTypeFromIdServices(array|int $idServices)
    {
        if (is_array($idServices)) {
            $idServicesList = implode(',', $idServices);
            $results = parent::select('id_SERVICES, subtype', "id_SERVICES IN ($idServicesList)");

            $services = [];
            foreach ($results as $result) {
                $services[$result['id_SERVICES']] = $result['subtype'];
            }

            return $services;
        } else {
            return parent::select('subtype', "id_SERVICES = $idServices ")[0]['subtype'];
        }
    }



    public function readIndividualActiveServicePaidCodes()
    {
        return parent::select('serviceCode', 'serviceGroupingType = "individual" AND billingCategory = "PaidService" AND status = "active" ');
    }

    public function readCollectiveActiveServicePaidCodes()
    {
        return parent::select('serviceCode', 'serviceGroupingType = "collective" AND billingCategory = "PaidService"  AND status = "active" ');
    }

    public function readServicePrices(array $serviceCodes)
    {
        $serviceCodes = "'" . implode("','", $serviceCodes) . "'";
        $prices = parent::select('serviceCode, priceOrDiscount AS price', "serviceCode IN ($serviceCodes)");

        $servicePrices = array();
        foreach ($prices as $priceInfo) {
            $servicePrices[$priceInfo['serviceCode']] = floatval($priceInfo['price']);
        }

        return $servicePrices;
    }

    public function readServicePrice(string $serviceCode)
    {
        $price = parent::select('priceOrDiscount AS price', "serviceCode = '$serviceCode'");

        $servicePrice = 0;
        if (count($price) > 0) {
            $servicePrice = floatval($price[0]['price']);
        }

        return $servicePrice;
    }


    public function readServiceDiscount(string $serviceCode)
    {
        return parent::select('priceOrDiscount', "serviceCode = '$serviceCode'  AND status = 'active'  ")[0]['priceOrDiscount'];
    }

    public function getServiceIdsFromCodes($serviceCodes)
    {
        if (is_array($serviceCodes)) {
            $serviceCodes = "'" . implode("','", $serviceCodes) . "'";
            $results = parent::select('id_SERVICES, serviceCode', "serviceCode IN ($serviceCodes)");
            $assocArray = [];
            foreach ($results as $result) {
                $assocArray[$result['serviceCode']] = $result['id_SERVICES'];
            }
            return $assocArray;
        } else {
            return parent::select('id_SERVICES', "serviceCode = '$serviceCodes' ")[0]['id_SERVICES'];
        }
    }



    //----------------------------------------------------
    public function createServices(array $data, bool $getId = false)
    {
        return parent::insert($data, $getId);
    }

    public function readServices()
    {
        return parent::select("*");
    }

    public function updateServices(array $data, string $where)
    {
        return parent::update($data, $where);
    }

    public function deleteServices(string $where = "")
    {
        return parent::delete($where);
    }
}
