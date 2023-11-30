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


//FALTA ESTRUCTURAR LOS DATOS PARA ENVIARLOS A CREAR EL HTML
        $invoiceCode = $invoiceData['invoiceCode'];
        $invoicedDate = $invoiceData['invoicedDate'];
        $price = $invoiceData['price'];

        $bookCode = $bookData['bookCode'];
        $direction = $bookData['direction'];
        $invoiced = $invoiceData['invoiced'];
        $adultsNumber = $bookData['adultsNumber'];
        $childsNumber = $bookData['childsNumber'];
        $infantsNumber = $bookData['infantsNumber'];

        $flightCode = $flightData['flightCode'];
        $date = $flightData['date'];
        $hour = $flightData['hour'];
        $price = $flightData['price'];

        $documentationType = $userData['documentationType'];
        $documentCode = $userData['documentCode'];

        $originIATA = $airportOriginData['IATA'];
        $originName = $airportOriginData['name'];

        $destinyIATA = $airportDestinyData['IATA'];
        $destinyName = $airportDestinyData['name'];

        //Recogemos un resumen de los servicios en $services
        $idServices = array_column($servicesInvoicesData, 'id_SERVICES');
        $serviceNames = $servicesModel->readSubTypeFromIdServices($idServices);
        $services = [];
        foreach ($servicesInvoicesData as $serviceInvoice) {
            $id_SERVICES = $serviceInvoice['id_SERVICES'];
            $addRemove = $serviceInvoice['addRemove'];
            $oldPrice = $serviceInvoice['oldPrice'];
        
            if (!isset($services[$id_SERVICES])) {
                $services[$id_SERVICES] = [
                    'name' => $serviceNames[$id_SERVICES],
                    'addRemove' => $addRemove,
                    'oldPrice' => $oldPrice,
                    'count' => 1
                ];
            } else {
                $services[$id_SERVICES]['count']++;
            }
        }

        // Imprime los datos para debug
        error_log('Invoice Data:');
        error_log(print_r($invoiceData, true));
        error_log('Book Data:');
        error_log(print_r($bookData, true));
        error_log('Flight Data:');
        error_log(print_r($flightData, true));
        error_log('User Data:');
        error_log(print_r($userData, true));
        error_log('Services Invoices Data:');
        error_log(print_r($servicesInvoicesData, true));
        error_log('Itinerary Data:');
        error_log(print_r($itineraryData, true));
        error_log('Airport Origin Data:');
        error_log(print_r($airportOriginData, true));
        error_log('Airport Destiny Data:');
        error_log(print_r($airportDestinyData, true));

        return [];
        /*
//flightCode
//flightPrice

//adultsNumber
//childrenNumber
//infantsNumber

//passengerServicesCode
//passengerServicesPrice

//bookServicesCode
//bookServicesPrice

//userData
//invoiceData
//BookData
            */
    }

    public function generateInvoiceHtml($invoiceData)
    {
    }

    public function generateInvoicePDF($invoiceHtml)
    {
    }
}
