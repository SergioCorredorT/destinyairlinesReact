<?php
require_once ROOT_PATH . '/Tools/SessionTool.php';
require_once ROOT_PATH . '/Tools/IniTool.php';
require_once ROOT_PATH . '/Models/FlightModel.php';
require_once ROOT_PATH . '/Models/ServicesModel.php';

class BookingDataEnricherTool
{
    public function getCompleteBookWithPricesFromSession(string $direction)
    {
        $bookData =    SessionTool::getAll()[$direction];

        $flightDetails      = &$bookData['flightDetails'];
        $passengersDetails  = &$bookData['passengersDetails'];
        $bookServicesDetails = &$bookData['bookServicesDetails'];

        $this->setFlightPrice($flightDetails);

        $this->setPassengersPrice($passengersDetails);

        $this->setBookServicesPrice($bookServicesDetails);

        return $bookData;
    }

    private function setFlightPrice(&$flightDetails)
    {
        $flightModel = new FlightModel();
        $flightPrice = $flightModel->getFlightPrice($flightDetails['flightCode']);
        $flightDetails['flightPrice'] = $flightPrice;
    }

    private function setPassengersPrice(&$passengersDetails)
    {
        $servicesModel = new ServicesModel();
        $serviceCodes = [];
        foreach ($passengersDetails as $passenger) {
            //Recogemos todos los servicios individuales contratados sin repetir de todos los pasajeros
            if (!empty($passenger['services'])) {
                foreach ($passenger['services'] as $serviceCode) {
                    $serviceCodes[$serviceCode] = $serviceCode;
                }
            }
        }

        if (!empty($serviceCodes)) {
            //Recogemos los precios de la variedad de servicios de nuestros pasajeros
            $servicesWithPrices = $servicesModel->readServicePrices($serviceCodes);

            //Sumamos todos los precios de los servicios individuales
            foreach ($passengersDetails as &$passenger) {
                if (!empty($passenger['services'])) {
                    foreach ($passenger['services'] as $serviceCode) {
                        $passenger['services'][$serviceCode] = $servicesWithPrices[$serviceCode];
                    }
                }
            }
        }
    }

    private function setBookServicesPrice(&$bookServicesDetails)
    {
        $servicesModel = new ServicesModel();
        if (!empty($bookServicesDetails)) {
            $bookServicesWithPrices = $servicesModel->readServicePrices($bookServicesDetails);
            foreach ($bookServicesDetails as $serviceCode) {
                $bookServicesDetails[$serviceCode] = $bookServicesWithPrices[$serviceCode];
            }
        }
    }
}
