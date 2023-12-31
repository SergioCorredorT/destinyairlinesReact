<?php
require_once ROOT_PATH . '/Models/ServicesModel.php';
class BookingPriceCalculatorTool
{
    public function calculateTotalPriceFromBookWithPrices(array $bookData): float
    {
        $totalPrice = 0;
        $iniTool = new IniTool(ROOT_PATH  . '/Config/cfg.ini');
        $databaseFieldMappings = $iniTool->getKeysAndValues("databaseFieldMappings");
        $priceSettings = $iniTool->getKeysAndValues("priceSettings");
        $numberPeopleForDiscountForMoreThanXPersons = intval($priceSettings['discountForMoreThanXPersons']) ?? 0;
        $discountPercentageForDiscountMoreThanXPersons = 0;
        if (count($bookData['passengersDetails']) > $numberPeopleForDiscountForMoreThanXPersons) {
            $servicesModel = new ServicesModel();
            $discountPercentageForDiscountMoreThanXPersons = $servicesModel->readServiceDiscount($databaseFieldMappings['discountForMoreThanXPersonsCode']);
        }

        // Suma el precio del vuelo para cada pasajero
        foreach ($bookData['passengersDetails'] as $passenger) {
            $totalPrice += $this->calculatePassengerPrice($passenger, $bookData, $priceSettings, $discountPercentageForDiscountMoreThanXPersons, $numberPeopleForDiscountForMoreThanXPersons);
        }

        // Suma el precio de los servicios de reserva
        $totalPrice += $this->calculateBookServicesPrice($bookData['bookServicesDetails']);

        return $totalPrice;
    }

    private function calculatePassengerPrice(array $passenger, array $bookData, array $priceSettings, int $discountPercentageForDiscountMoreThanXPersons, int $numberPeopleForDiscountForMoreThanXPersons): float
    {
        $flightPrice = $bookData['flightDetails']['flightPrice'];
        $ageCategoryDiscountPercentage = intval($priceSettings[$passenger['ageCategory'] . 'DiscountPercentage']) ?? 0;
        $passengerPrice = $flightPrice * (1 - $ageCategoryDiscountPercentage / 100);

        if (count($bookData['passengersDetails']) > $numberPeopleForDiscountForMoreThanXPersons) {
            $passengerPrice = $passengerPrice * (1 - $discountPercentageForDiscountMoreThanXPersons / 100);
        }

        // Suma el precio de los servicios de cada pasajero
        $passengerPrice += $this->calculatePassengerServicesPrice($passenger);

        return $passengerPrice;
    }

    public function calculatePassengerServicesPrice(array $passenger): float
    {
        $servicesPrice = 0;
        if (!empty($passenger['services'])) {
            foreach ($passenger['services'] as $servicePrice) {
                $servicesPrice += $servicePrice;
            }
        }
        return $servicesPrice;
    }

    public function calculateBookServicesPrice(array $bookServicesDetails): float
    {
        $servicesPrice = 0;
        if (!empty($bookServicesDetails)) {
            foreach ($bookServicesDetails as $servicePrice) {
                $servicesPrice += $servicePrice;
            }
        }
        return $servicesPrice;
    }

    public function getPassengersServicesSummary(array $passengers): array
    {
        $services = [];
        foreach($passengers as $passenger) {
            foreach($passenger['services'] as $serviceCode => $price) {
                if (!isset($services[$serviceCode])) {
                    $services[$serviceCode] = ['priceByOne' => $price, 'number' => 1];
                } else {
                    $services[$serviceCode]['number']++;
                }
            }
        }
        return $services;
    }
}
