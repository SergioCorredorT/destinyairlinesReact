<?php
class CheckinTool
{

    public function __construct()
    {
        spl_autoload_register(function (string $class_name) {

            $file = ROOT_PATH . '/models/' . $class_name . '.php';

            if (file_exists($file)) {

                require_once $file;
            }
        });
    }

    public function generateCheckinData(array $checkinData): array|bool
    {
        if (!isset($checkinData['bookCode'], $checkinData['flightDate'], $checkinData['flightHour'], $checkinData['idItinerary'], $checkinData['flightCode'])) {
            return false;
        }

        $itineraryModel = new ItineraryModel();
        $airportModel = new AirportModel();
        $bookModel = new BookModel();
        $passengerModel = new PassengerModel();

        $bookCode = $checkinData['bookCode'];
        $flightDate = $checkinData['flightDate'];
        $flightHour = $checkinData['flightHour'];
        $flightCode = $checkinData['flightCode'];
        $originDestiny = $itineraryModel->readOriginDestiny($checkinData['idItinerary']);
        $originAirportName = $airportModel->readAirportNameFromIdAirport($originDestiny['origin']);
        $destinyAirportName = $airportModel->readAirportNameFromIdAirport($originDestiny['destiny']);

        $idBook = $bookModel->readIdBookFromBookCode($bookCode);
        $passengersData = $passengerModel->readFirstNameLastNamePassengerCode($idBook);

        return [
            'flightCode' => $flightCode,
            'flightDate' => $flightDate,
            'flightHour' => $flightHour,
            'origin' => $originAirportName,
            'destiny' => $destinyAirportName,
            'passengersData' => $passengersData
        ];
    }

    public function generateBoardingPassHtml(array $checkinData): string
    {
        require_once ROOT_PATH . '/Tools/TemplateTool.php';
        $templateTool = new TemplateTool();
        return $templateTool::ApplyPageTemplate($checkinData, 'boardingPassTemplate');
    }
}
