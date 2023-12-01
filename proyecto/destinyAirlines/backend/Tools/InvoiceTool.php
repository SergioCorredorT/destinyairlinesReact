<?php
class InvoiceTool
{

    public function __construct()
    {
        spl_autoload_register(function (string $class_name) {

            $file = './models/' . $class_name . '.php';

            if (file_exists($file)) {

                require_once $file;
            }
        });
    }

    public function generateInvoiceData($idUser, $idInvoice)
    {
        $invoiceModel = new InvoiceModel();
        $bookModel = new BookModel();
        $flightModel = new FlightModel();
        $userModel = new UserModel();
        $servicesInvoicesModel = new ServicesInvoicesModel();
        $itineraryModel = new ItineraryModel();
        $airportModel = new AirportModel();
        $servicesModel = new ServicesModel();

        $invoiceData = $invoiceModel->getInvoiceFromIdInvoice($idInvoice);
        $bookData = $bookModel->readBookFromIdBook($invoiceData['id_BOOKS']);
        $flightData = $flightModel->getFlightFromIdFlight($bookData['id_FLIGHTS']);
        $userData = $userModel->readUserById($idUser);
        $servicesInvoicesData = $servicesInvoicesModel->readServicesInvoicesFromIdInvoice($idInvoice);
        $itineraryData = $itineraryModel->readItineraryFromIdItinerary($flightData['id_ITINERARIES']);
        $airportOriginData = $airportModel->readAirportFromIdAirport($itineraryData['origin']);
        $airportDestinyData = $airportModel->readAirportFromIdAirport($itineraryData['destiny']);

        //Recogemos un resumen de los servicios en $services
        $services = [];
        if (!empty($servicesInvoicesData)) {
            $idServices = array_column($servicesInvoicesData, 'id_SERVICES');
            $serviceNames = $servicesModel->readSubTypeFromIdServices($idServices);
            foreach ($servicesInvoicesData as $serviceInvoice) {
                $id_SERVICES = $serviceInvoice['id_SERVICES'];
                $addRemove = $serviceInvoice['addRemove'];
                $oldPrice = $serviceInvoice['oldPrice'];

                if (!isset($services[$id_SERVICES])) {
                    $services[$id_SERVICES] = [
                        'name' => $serviceNames[$id_SERVICES],
                        'addRemove' => $addRemove,
                        'price' => $oldPrice,
                        'count' => 1
                    ];
                } else {
                    $services[$id_SERVICES]['count']++;
                }
            }
        }

        return [
            'invoiceData' => [
                'invoiceCode' => $invoiceData['invoiceCode'],
                'invoicedDate' => $invoiceData['invoicedDate'],
                'price' => $invoiceData['price']
            ],
            'bookData' => [
                'bookCode' => $bookData['bookCode'],
                'direction' => $bookData['direction'],
                'adultsNumber' => $bookData['adultsNumber'],
                'childsNumber' => $bookData['childsNumber'],
                'infantsNumber' => $bookData['infantsNumber']
            ],
            'flightData' => [
                'flightCode' => $flightData['flightCode'],
                'date' => $flightData['date'],
                'hour' => $flightData['hour'],
                'price' => $flightData['price']
            ],
            'userData' => [
                'documentationType' => $userData['documentationType'],
                'documentCode' => $userData['documentCode'],
                'firstName' => $userData['firstName'],
                'lastName' => $userData['lastName'],
                'zipCode' => $userData['zipCode'],
                'emailAddress' => $userData['emailAddress']
            ],
            'airportOriginData' => [
                'IATA' => $airportOriginData['IATA'],
                'name' => $airportOriginData['name']
            ],
            'airportDestinyData' => [
                'IATA' => $airportDestinyData['IATA'],
                'name' => $airportDestinyData['name']
            ],
            'services' => $services
        ];
    }

    public function generateInvoiceHtml($invoiceData)
    {
        require_once './Tools/TemplateTool.php';
        $templateTool = new TemplateTool();
        $invoiceHtml = $templateTool::ApplyPageTemplate($invoiceData, 'invoiceTemplate');

    }

    public function generateInvoicePDF($invoiceHtml)
    {
    }
}
