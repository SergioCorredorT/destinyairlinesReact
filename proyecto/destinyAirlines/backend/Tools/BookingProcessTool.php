<?php

class BookingProcessTool
{    
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

    public function validateSeatAvailability($passengers, $flightCode){
        require_once './Models/FlightModel.php';
        require_once './Models/AirplaneModel.php';

        $flightModel = new FlightModel();
        //$airplaneModel = new AirplaneModel();

        //Primero comprobamos si el número de asientos necesarios es menor que los disponibles
        $seatsNeeded = $this->getSeatsNeeded($passengers);
        $freeSeats = $flightModel->getFreeSeats($flightCode);
        //En principio era buena idea, pero pueden haber multiples reasignaciones simultáneas desde distintas conexiones
        /*if (is_null($freeSeats)) {
            $idAirplane = $flightModel->getIdAirplanes($flightCode);
            $freeSeats = $airplaneModel->getSeats($idAirplane);
        }*/

        if ($seatsNeeded > $freeSeats) {
            return false;
        }

        return true;
    }

    private function getSeatsNeeded(array $passengers)
    {
        $count = 0;
        foreach ($passengers as $passenger) {
            if ($passenger['ageCategory'] == 'adult' || $passenger['ageCategory'] == 'child') {
                $count++;
            }
        }
        return $count;
    }

    public function savePrimaryContactInfo($primaryContactDetails) {
        require_once './Models/PrimaryContactInformationModel.php';
        $PrimaryContactInformationModel = new PrimaryContactInformationModel();
        [$idPrimaryContactInfo] = $PrimaryContactInformationModel->createPrimaryContactInformation($primaryContactDetails, true);
        if (!$idPrimaryContactInfo) {
            throw new Exception('Catched exception creating primary Contact Information');
        }
        return $idPrimaryContactInfo;
    }

    public function saveBook($passengers, $flightCode, $direction, $idPrimaryContactInfo, $idUser) {
        require_once './Tools/BookingProcessTool.php';
        require_once './Models/BookModel.php';
        require_once './Models/FlightModel.php';
        $BookingProcessTool = new BookingProcessTool();
        $flightModel = new FlightModel();
        $BookModel = new BookModel();
        $countsAgeCategories = $BookingProcessTool->getPassengersNumberByAgeCategory($passengers);
        [$idBook] = $BookModel->createBooks([
            'id_FLIGHTS' => $flightModel->getIdFlightFromFlightCode($flightCode),
            'id_USERS' => $idUser,
            'id_PRIMARY_CONTACT_INFORMATIONS' => $idPrimaryContactInfo,
            'bookCode' => $BookingProcessTool->generateUUID(),
            'direction' => $direction,
            'adultsNumber' => $countsAgeCategories['adult'],
            'childsNumber' => $countsAgeCategories['child'],
            'infantsNumber' => $countsAgeCategories['infant']
        ], true);
        if (!$idBook) {
            throw new Exception('Catched exception creating book');
        }
        return $idBook;
    }

    public function saveInvoice($idBook, $totalPrice) {
        require_once './Models/InvoiceModel.php';
        $invoiceModel = new InvoiceModel();

        [$idInvoice] = $invoiceModel->createInvoices([
            'id_BOOKS' => $idBook,
            'invoiceCode' => $this->generateUUID(),
            'invoicedDate' => date('Y-m-d H:i:s'),
            'price' => $totalPrice
        ], true);
        if (!$idInvoice) {
            throw new Exception('Catched exception creating invoice');
        }
        return $idInvoice;
    }

    public function saveBookServicesAndServicesInvoices($idBook, $bookServicesAndPrice, $idInvoice) {
        require_once './Models/ServicesModel.php';
        require_once './Models/BookServiceModel.php';
        require_once './Models/ServicesInvoicesModel.php';
        $servicesModel = new ServicesModel();
        $bookServiceModel = new BookServiceModel();
        $servicesInvoicesModel = new ServicesInvoicesModel();

        $bookServices = [];
        $individualServicesInvoices = [];

        $serviceIdsFromCodes = $servicesModel->getServiceIdsFromCodes(array_keys($bookServicesAndPrice));
        foreach ($bookServicesAndPrice as $serviceCode => $price) {
            $bookServices[] = ['id_BOOKS' => $idBook, 'id_SERVICES' => $serviceIdsFromCodes[$serviceCode]];

            $individualServicesInvoices[] = [
                'id_INVOICES' => $idInvoice,
                'id_SERVICES' => $serviceIdsFromCodes[$serviceCode],
                'id_PASSENGERS' => NULL,
                'addRemove' => 'add',
                'oldPrice' => $price,
            ];
        }
        $bookServicesRsp = $bookServiceModel->createMultipleBookServices($bookServices);
        if (!$bookServicesRsp) {
            throw new Exception('Catched exception creating book services');
        }

        $individualServicesInvoiceRsp = $servicesInvoicesModel->createMultipleServicesInvoices($individualServicesInvoices);
        if (!$individualServicesInvoiceRsp) {
            throw new Exception('Catched exception creating service invoices');
        }
    }

    public function savePassengersAndGetAddiInfoAndPassServAndServInvo($passengers, $idBook, $idInvoice) {
        require_once './Models/PassengerModel.php';
        require_once './Models/ServicesModel.php';

        $passengerModel = new PassengerModel();
        $servicesModel = new ServicesModel();

        $passengerServiceData = [];
        $servicesInvoicesData = [];
        $additionalInformationData = [];
    
        foreach ($passengers as $passenger) {
            [$idPassenger] = $passengerModel->createPassengers([
                'passengerCode' => $this->generateUUID(),
                'documentationType' => $passenger['documentationType'] ?? '',
                'documentCode' => $passenger['documentCode'] ?? '',
                'expirationDate' => $passenger['expirationDate'] ?? '',
                'title' => $passenger['title'] ?? '',
                'firstName' => $passenger['firstName'] ?? '',
                'lastName' => $passenger['lastName'] ?? '',
                'ageCategory' => $passenger['ageCategory'] ?? '',
                'nationality' => $passenger['nationality'] ?? '',
                'country' => $passenger['country'] ?? ''
            ], true);
            if (!$idPassenger) {
                throw new Exception('Catched exception creating passengers');
            }

            $additionalInformationData[] = [
                'id_PASSENGERS' => $idPassenger,
                'dateBirth' => $passenger['dateBirth'] ?? '',
                'assistiveDevices' => $passenger['assistiveDevices'] ?? 'NULL',
                'medicalEquipment' => $passenger['medicalEquipment'] ?? 'NULL',
                'mobilityLimitations' => $passenger['mobilityLimitations'] ?? 'NULL',
                'communicationNeeds' => $passenger['communicationNeeds'] ?? 'NULL',
                'medicationRequirements' => $passenger['medicationRequirements'] ?? 'NULL'
            ];

            // insertar passengers_books_services
            $idsServices = $servicesModel->getServiceIdsFromCodes(array_keys($passenger['services']));
            foreach ($passenger['services'] as $serviceCode => $price) {
                $passengerServiceData[] = [
                    'id_PASSENGERS' => $idPassenger,
                    'id_BOOKS' => $idBook,
                    'id_SERVICES' => $idsServices[$serviceCode]
                ];

                // insertar individual services_invoice
                $servicesInvoicesData[] = [
                    'id_INVOICES' => $idInvoice,
                    'id_SERVICES' => $idsServices[$serviceCode],
                    'id_PASSENGERS' => $idPassenger,
                    'addRemove' => 'add',
                    'oldPrice' => $price,
                ];
            }
        }

        return [$additionalInformationData, $passengerServiceData, $servicesInvoicesData];
    }
    
    public function createAdditionalInformation($additionalInformationData) {
        require_once './Models/AdditionalInformationModel.php';
        $additionalInformationModel = new AdditionalInformationModel();
        $additionalInformationRsp = $additionalInformationModel->createMultipleAdditionalInformations($additionalInformationData);
        if (!$additionalInformationRsp) {
            throw new Exception('Catched exception additional Information');
        }
    }
    
    public function createPassengerBookService($passengerServiceData) {
        require_once './Models/PassengerBookServiceModel.php';
        $passengerBookServiceModel = new PassengerBookServiceModel();
        $passengerBookServiceRsp = $passengerBookServiceModel->createMultiplePassengerService($passengerServiceData);
        if (!$passengerBookServiceRsp) {
            throw new Exception('Catched exception creating passenger book services');
        }
    }
    
    public function createServicesInvoices($servicesInvoicesData) {
        require_once './Models/ServicesInvoicesModel.php';
        $servicesInvoicesModel = new ServicesInvoicesModel();
        $servicesInvoicesRsp = $servicesInvoicesModel->createMultipleServicesInvoices($servicesInvoicesData);
        if (!$servicesInvoicesRsp) {
            throw new Exception('Catched exception creating service invoices');
        }
    }

    public function decreaseAvailableSeats($passengers, $flightCode) {
        $flightModel = new FlightModel();
        //Primero comprobamos si el número de asientos necesarios es menor que los disponibles
        $seatsNeeded = $this->getSeatsNeeded($passengers);
        $freeSeats = $flightModel->getFreeSeats($flightCode);

        if ($seatsNeeded <= $freeSeats) {
            $decreaseAvailableSeatsRsp= $flightModel->decreaseAvailableSeats($seatsNeeded, $flightCode);
            if(!$decreaseAvailableSeatsRsp){
                throw new Exception('Catched exception decreasing available seats');
            }
            else{
                return $decreaseAvailableSeatsRsp;
            }
        }

        return false;
    }
}
