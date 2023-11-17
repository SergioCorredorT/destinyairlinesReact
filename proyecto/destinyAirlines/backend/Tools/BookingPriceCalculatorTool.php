<?php
require_once './Models/ServicesModel.php';
class BookingPriceCalculatorTool
{
    public function calculateTotalPriceFromBookWithPrices($bookData)
    {
        $totalPrice = 0;
        $iniTool = new IniTool('./Config/cfg.ini');
        $priceSettings = $iniTool->getKeysAndValues("priceSettings");
        $numberPeopleForDiscountForMoreThanXPersons = intval($priceSettings['discountForMoreThanXPersons']) ?? 0;
        $discountPercentageForDiscountMoreThanXPersons = 0;
        if (count($bookData['passengersDetails']) > $numberPeopleForDiscountForMoreThanXPersons) {
            $servicesModel = new ServicesModel();
            $discountPercentageForDiscountMoreThanXPersons = $servicesModel->readServiceDiscount('SRV009');
        }

        // Suma el precio del vuelo para cada pasajero
        foreach ($bookData['passengersDetails'] as $passenger) {
            $totalPrice += $this->calculatePassengerPrice($passenger, $bookData, $priceSettings, $discountPercentageForDiscountMoreThanXPersons, $numberPeopleForDiscountForMoreThanXPersons);
        }

        // Suma el precio de los servicios de reserva
        $totalPrice += $this->calculateBookServicesPrice($bookData);

        return $totalPrice;
    }

    private function calculatePassengerPrice($passenger, $bookData, $priceSettings, $discountPercentageForDiscountMoreThanXPersons, $numberPeopleForDiscountForMoreThanXPersons)
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

    private function calculatePassengerServicesPrice($passenger)
    {
        $servicesPrice = 0;
        if (!empty($passenger['services'])) {
            foreach ($passenger['services'] as $servicePrice) {
                $servicesPrice += $servicePrice;
            }
        }
        return $servicesPrice;
    }

    private function calculateBookServicesPrice($bookData)
    {
        $servicesPrice = 0;
        if (!empty($bookData['bookServicesDetails'])) {
            foreach ($bookData['bookServicesDetails'] as $servicePrice) {
                $servicesPrice += $servicePrice;
            }
        }
        return $servicesPrice;
    }
}
