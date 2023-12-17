<?php
require_once "./Models/BaseModel.php";
final class ServicesModel extends BaseModel
{
    private const table = "SERVICES";

    public function __construct()
    {
        parent::__construct(self::table);
    }

    public function readSubTypeAndBillingCategoryFromIdServices(array|int $idServices): bool|array|string
    {
        if (is_array($idServices)) {
            $idServicesList = implode(',', $idServices);
            $results = parent::select('id_SERVICES, subtype, billingCategory', "id_SERVICES IN ($idServicesList)");

            $services = [];
            foreach ($results as $result) {
                $services[$result['id_SERVICES']] = ['subtype'=>$result['subtype'], 'billingCategory'=>$result['billingCategory']];
            }

            return $services;
        } else {
            return parent::select('subtype', "id_SERVICES = $idServices ")[0]['subtype'];
        }
    }

    public function readSubTypeFromIdServices(array|int $idServices): bool|array|string
    {
        if (is_array($idServices)) {
            $idServicesList = implode(',', $idServices);
            $results = parent::select('id_SERVICES, subtype', "id_SERVICES IN ($idServicesList)");

            $services = [];
            foreach ($results as $result) {
                $services[$result['id_SERVICES']] = ['subtype'=>$result['subtype']];
            }

            return $services;
        } else {
            return parent::select('subtype', "id_SERVICES = $idServices ")[0]['subtype'];
        }
    }

    public function readIndividualActiveServicePaidCodes(): bool|array
    {
        return parent::select('serviceCode', 'serviceGroupingType = "individual" AND billingCategory = "PaidService" AND status = "active" ');
    }

    public function readCollectiveActiveServicePaidCodes(): bool|array
    {
        return parent::select('serviceCode', 'serviceGroupingType = "collective" AND billingCategory = "PaidService"  AND status = "active" ');
    }

    public function readServicePrices(array $serviceCodes): array
    {
        $serviceCodes = "'" . implode("','", $serviceCodes) . "'";
        $prices = parent::select('serviceCode, priceOrDiscount AS price', "serviceCode IN ($serviceCodes)");

        $servicePrices = array();
        foreach ($prices as $priceInfo) {
            $servicePrices[$priceInfo['serviceCode']] = floatval($priceInfo['price']);
        }

        return $servicePrices;
    }

    public function readServicePrice(string $serviceCode): float|int
    {
        $price = parent::select('priceOrDiscount AS price', "serviceCode = '$serviceCode'");

        $servicePrice = 0;
        if (count($price) > 0) {
            $servicePrice = floatval($price[0]['price']);
        }

        return $servicePrice;
    }


    public function readServiceDiscount(string $serviceCode): bool|array
    {
        return parent::select('priceOrDiscount', "serviceCode = '$serviceCode'  AND status = 'active'  ")[0]['priceOrDiscount'];
    }

    public function getServiceIdsFromCodes(string|array $serviceCodes): bool|string|array
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
}
