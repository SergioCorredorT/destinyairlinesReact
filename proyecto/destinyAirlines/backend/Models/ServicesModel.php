<?php
require_once "./Models/BaseModel.php";
final class ServicesModel extends BaseModel
{
    private const table = "SERVICES";

    public function __construct()
    {
        parent::__construct(self::table);
    }

    public function readIndividualServicePaidCodes()
    {
        return parent::select('serviceCode','serviceGroupingType = "individual" AND billingCategory = "PaidService" ');
    }

    public function readCollectiveServicePaidCodes()
    {
        return parent::select('serviceCode','serviceGroupingType = "collective" AND billingCategory = "PaidService" ');
    }
    
    public function readServicePrices(array $services)
    {
        $serviceCodes = "'" . implode("','", $services) . "'";
        $prices = parent::select('serviceCode, priceOrDiscount AS price', "serviceCode IN ($serviceCodes)");
    
        $servicePrices = array();
        foreach ($prices as $priceInfo) {
            $servicePrices[$priceInfo['serviceCode']] = floatval($priceInfo['price']);
        }
    
        return $servicePrices;
    }
    

//----------------------------------------------------
    public function createServices(array $data)
    {
        return parent::insert($data);
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
