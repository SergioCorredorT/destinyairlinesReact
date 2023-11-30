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

        $invoiceData = $invoiceModel->getInvoiceFromIdInvoice($idInvoice);
        $bookData = $bookModel->readBookFromIdBook($invoiceData['id_BOOKS']);
        $flightData = $flightModel->getFlightFromIdFlight($bookData['id_FLIGHTS']);
        $userData = $userModel->readUserById($idUser);
        $servicesInvoicesData = $servicesInvoicesModel->readServicesInvoicesFromIdInvoice($idInvoice);
        $itineraryData = $itineraryModel->readItineraryFromIdItinerary($flightData['id_ITINERARIES']);
        $airportOriginData = $airportModel->readAirportFromIdAirport($itineraryData['origin']);
        $airportDestinyData = $airportModel->readAirportFromIdAirport($itineraryData['destiny']);
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
