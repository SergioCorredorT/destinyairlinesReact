<?php
require_once './Database/Database.php';
require_once './Tools/IniTool.php';
require_once './Models/BaseMultiModel.php';
final class MultiModel extends BaseMultiModel
{
    public function __construct()
    {
        parent::__construct();
        spl_autoload_register(function (string $class_name) {

            $file = './models/' . $class_name . '.php';

            if (file_exists($file)) {

                require_once $file;
            }
        });
    }

    public function getSummaryBooks($idUser)
    {
        $sql =
            'SELECT b.bookCode, b.checkinDate, f.date, a.name AS destiny
        FROM books b
        JOIN flights f ON b.id_FLIGHTS = f.id_FLIGHTS
        JOIN itineraries i ON f.id_ITINERARIES = i.id_ITINERARIES
        JOIN airports a ON i.destiny = a.id_AIRPORTS
        WHERE b.id_USERS = :idUser';

        return parent::executeSql($sql, ['idUser' => $idUser]);
    }

    public function getBookInfo($bookCode, $idUser)
    {
        require_once './Models/BookModel.php';
        require_once './Tools/ArrayTool.php';
        $arrayTool = new ArrayTool();
        $bookModel = new BookModel();
        if (!$bookModel->checkBookCodeWithIdUser($bookCode, $idUser)) {
            return false;
        }

        $book_flight_results = $this->getBookAndFlightInfo($bookCode);
        if (!$book_flight_results) {
            return false;
        }

        $idPrimaryContactInfo = $book_flight_results[0]['id_PRIMARY_CONTACT_INFORMATIONS'];
        $primaryContactInfo = new PrimaryContactInformationModel();
        $primaryContactInfoResults = $primaryContactInfo->readPrimaryContactInformationFromId($idPrimaryContactInfo);
        if (!$primaryContactInfoResults) {
            return false;
        }

        $passengers_additionalInformations_results = $this->getPassengerWithAdditionalInfoFromIdBook($book_flight_results[0]['id_BOOKS']);

        $invoiceModel = new InvoiceModel();
        $invoiceResults = $invoiceModel->getInvoicesForGetBookInfoFromIdBook($book_flight_results[0]['id_BOOKS']);
        if (!$invoiceResults) {
            return false;
        }

        $idInvoices = [];
        foreach ($invoiceResults as $invoice) {
            array_push($idInvoices, $invoice['id_INVOICES']);
        }
        $servicesInvoicesModel = new ServicesInvoicesModel();
        $servicesModel = new ServicesModel();
        $servicesInvoicesData = $servicesInvoicesModel->readIdPassengerIdServicesAddRemoveOldPriceFromIdInvoice($idInvoices);
        $actualServicesInvoices = $this->getActualServicesInvoices($servicesInvoicesData);
        $idsServices = $arrayTool->getUniqueValues($actualServicesInvoices, 'id_SERVICES');
        $idServicesWithSubtype = $servicesModel->readSubTypeFromIdServices($idsServices);
        $actualServicesInvoicesWithSubtypes = $this->replaceIdServiceFieldWithServiceName($actualServicesInvoices, $idServicesWithSubtype);
        $groupedServicesByPassenger = $arrayTool->groupByField($actualServicesInvoicesWithSubtypes, 'id_PASSENGERS');

        return [
            'book' => [
                'bookCode' => $book_flight_results[0]['bookCode'],
                'direction' => $book_flight_results[0]['direction'],
                'checkinDate' => $book_flight_results[0]['checkinDate']
            ],
            'flight' => [
                'date' => $book_flight_results[0]['date'],
                'hour' => $book_flight_results[0]['hour'],
                'arrivalHour' => $book_flight_results[0]['arrivalHour'],
                'origin' => $book_flight_results[0]['origin'],
                'destiny' => $book_flight_results[0]['destiny']
            ],
            'primaryContactInfo' => [
                'documentationType' => $primaryContactInfoResults[0]['documentationType'],
                'documentCode' => $primaryContactInfoResults[0]['documentCode'],
                'expirationDate' => $primaryContactInfoResults[0]['expirationDate'],
                'firstName' => $primaryContactInfoResults[0]['firstName'],
                'lastName' => $primaryContactInfoResults[0]['lastName'],
                'emailAddress' => $primaryContactInfoResults[0]['emailAddress'],
                'phoneNumber1' => $primaryContactInfoResults[0]['phoneNumber1'],
                'phoneNumber2' => $primaryContactInfoResults[0]['phoneNumber2'],
                'country' => $primaryContactInfoResults[0]['country'],
                'townCity' => $primaryContactInfoResults[0]['townCity'],
                'streetAddress' => $primaryContactInfoResults[0]['streetAddress'],
                'zipCode' => $primaryContactInfoResults[0]['zipCode'],
                'companyName' => $primaryContactInfoResults[0]['companyName'],
                'companyTaxNumber' => $primaryContactInfoResults[0]['companyTaxNumber'],
                'companyPhoneNumber' => $primaryContactInfoResults[0]['companyPhoneNumber'],
                'dateBirth' => $primaryContactInfoResults[0]['dateBirth']
            ],
            'invoice' => array_map(function ($invoice) {
                return [
                    'invoiceCode' => $invoice['invoiceCode'],
                    'invoicedDate' => $invoice['invoicedDate'],
                    'price' => $invoice['price'],
                    'isPaid' => $invoice['isPaid']
                ];
            }, $invoiceResults),
            'passengersInfo' => array_map(function ($passenger) use ($groupedServicesByPassenger) {
                $passengerServices = '';
                if(isset($groupedServicesByPassenger[$passenger['id_PASSENGERS']])) {
                    $passengerServices = $groupedServicesByPassenger[$passenger['id_PASSENGERS']];
                }

                return [
                    'documentationType' => $passenger['documentationType'],
                    'documentCode' => $passenger['documentCode'],
                    'expirationDate' => $passenger['expirationDate'],
                    'firstName' => $passenger['firstName'],
                    'lastName' => $passenger['lastName'],
                    'ageCategory' => $passenger['ageCategory'],
                    'nationality' => $passenger['nationality'],
                    'country' => $passenger['country'],
                    'dateBirth' => $passenger['dateBirth'],
                    'assistiveDevices' => $passenger['assistiveDevices'],
                    'medicalEquipment' => $passenger['medicalEquipment'],
                    'mobilityLimitations' => $passenger['mobilityLimitations'],
                    'communicationNeeds' => $passenger['communicationNeeds'],
                    'medicationRequirements' => $passenger['medicationRequirements'],
                    'services' => $passengerServices
                ];
            }, $passengers_additionalInformations_results),
            'services' => $groupedServicesByPassenger[0]
        ];
    }

    public function getPassengerWithAdditionalInfoFromIdBook($idBook)
    {
        $sqlMultiTable =
            'SELECT p.*, a.*
            FROM passengers p
            JOIN additional_informations a ON p.id_PASSENGERS = a.id_PASSENGERS
            WHERE p.id_BOOKS = :idBook';
        return parent::executeSql($sqlMultiTable, ['idBook' => $idBook]);
    }

    public function getBookAndFlightInfo($bookCode)
    {
        $sqlMultiTable =
            'SELECT b.id_BOOKS, b.bookCode, b.direction, b.checkinDate, f.date, f.hour, f.arrivalHour, a1.name AS origin, a2.name AS destiny, id_PRIMARY_CONTACT_INFORMATIONS
            FROM books b
            JOIN flights f ON b.id_FLIGHTS = f.id_FLIGHTS
            JOIN itineraries i ON f.id_ITINERARIES = i.id_ITINERARIES
            JOIN airports a1 ON i.origin = a1.id_AIRPORTS
            JOIN airports a2 ON i.destiny = a2.id_AIRPORTS
            WHERE b.bookCode = :bookCode';
        return parent::executeSql($sqlMultiTable, ['bookCode' => $bookCode]);
    }

    public function removeBook($bookCode, $idUser) {
        
        //eliminando
    }

    private function getActualServicesInvoices($servicesInvoicesData)
    {
        $filteredData = [];
        $previousKeys = [];

        foreach ($servicesInvoicesData as $key => $data) {
            if ($data['addRemove'] === 'remove') {
                unset($filteredData[$key]);
                foreach ($previousKeys as $prevKey) {
                    if (
                        $filteredData[$prevKey]['id_SERVICES'] === $data['id_SERVICES'] &&
                        $filteredData[$prevKey]['id_PASSENGERS'] === $data['id_PASSENGERS']
                    ) {
                        unset($filteredData[$prevKey]);
                        break;
                    }
                }
            } else {
                $filteredData[$key] = $data;
                $previousKeys[] = $key;
            }
        }

        return $filteredData;
    }

    private function replaceIdServiceFieldWithServiceName($actualServicesInvoice, $nameServices)
    {
        foreach ($actualServicesInvoice as $key => $data) {
            $idService = $data['id_SERVICES'];
            if (isset($nameServices[$idService]['subtype'])) {
                $actualServicesInvoice[$key]['serviceName'] = $nameServices[$idService]['subtype'];
                unset($actualServicesInvoice[$key]['id_SERVICES']);
            }
        }
        return $actualServicesInvoice;
    }
}
