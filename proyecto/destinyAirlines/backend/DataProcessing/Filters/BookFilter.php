<?php
require_once ROOT_PATH . '/DataProcessing/Filters/BaseFilter.php';
final class BookFilter  extends BaseFilter
{
    public function __construct()
    {
        parent::__construct();
    }

    public function filterFlightData(array $POST): array
    {
        $flightDetails = [
            'flightCode'      => $POST['flightCode'] ?? '',
            'adultsNumber'    => $POST['adultsNumber'] ?? '',
            'childsNumber'    => $POST['childsNumber'] ?? '',
            'infantsNumber'   => $POST['infantsNumber'] ?? ''
        ];
        return $flightDetails;
    }

    public function filterPassengersData(array $POST): array
    {
        //Preparamos datos para procesarlos
        $FrontendDataHelpersTool = new FrontendDataHelpersTool();
        $NestedPOST = $FrontendDataHelpersTool->processNestedKeys($POST);
        $passengers = $NestedPOST['passengers'];
        $passengersDetails = [];

        //Vamos recogiendo los datos de los pasajeros
        foreach ($passengers as $passenger) {
            $passengerDetails = $this->filterPassengerData($passenger);

            //Cada pasajero lo saneamos y validamos
            $passengerDetails = $this->processData->processData($passengerDetails, 'Passenger');

            if (!$passengerDetails) {
                return false;
            }

            $timeTool = new TimeTool();
            $passengerDetails['ageCategory'] = $timeTool->getAgeCategory($passengerDetails['dateBirth']);

            array_push($passengersDetails, $passengerDetails);
        }
        return $passengersDetails;
    }

    private function filterPassengerData(array $passenger): array
    {
        $keys_default = [
            'documentationType' => '',
            'documentCode' => '',
            'expirationDate' => '',
            'title' => null,
            'firstName' => '',
            'lastName' => '',
            'nationality' => '',
            'country' => '',
            'dateBirth' => '',
            'assistiveDevices' => null,
            'medicalEquipment' => null,
            'mobilityLimitations' => null,
            'communicationNeeds' => null,
            'medicationRequirements' => null,
            'services' => null
        ];

        $passengerDetails = [];

        foreach ($keys_default as $key => $defaultValue) {
            $passengerDetails[$key] = !isset($passenger[$key]) || $passenger[$key] === "" ? $defaultValue : $passenger[$key];
        }

        return $passengerDetails;
    }

    public function filterBookServicesData(array $POST): array
    {
        //No ponemos aquí las variables admitidas porque todas son opcionales (valor por defecto null), y
        // porque en el validate ya se hace una solicitud a BBDD para comprobar las key y value que se envíen,
        // así evitamos una llamada extra a BBDD
        $FrontendDataHelpersTool = new FrontendDataHelpersTool();
        $NestedPOST = $FrontendDataHelpersTool->processNestedKeys($POST);
        return $NestedPOST;
    }

    public function filterPrimaryContactInformationData(array $POST): array
    {
        $primaryContactDetails = [
            'documentationType' => '',
            'documentCode' => '',
            'expirationDate' => '',
            'title' => null,
            'firstName' => '',
            'lastName' => '',
            'emailAddress' => '',
            'phoneNumber1' => '',
            'phoneNumber2' => null,
            'country' => '',
            'townCity' => '',
            'streetAddress' => '',
            'zipCode' => '',
            'companyName' => null,
            'companyTaxNumber' => null,
            'companyPhoneNumber' => null,
            'dateBirth' => ''
        ];

        foreach ($primaryContactDetails as $key => $defaultValue) {
            $primaryContactDetails[$key] = $POST[$key] ?? $defaultValue;
        }
        return $primaryContactDetails;
    }

    public function filterCheckinData(array $POST): array
    {
        $checkinDetails = [
            'accessToken'       => $POST['accessToken'] ?? '',
            'bookCode'          => $POST['bookCode'] ?? ''
        ];

        return $checkinDetails;
    }

    public function filterBookInfoData(array $POST): array
    {
        $bookInfo = [
            'accessToken'       => $POST['accessToken'] ?? '',
            'bookCode'          => $POST['bookCode'] ?? ''
        ];

        return $bookInfo;
    }

    public function filterCancelBookData(array $POST): array
    {
        $bookInfo = [
            'accessToken'       => $POST['accessToken'] ?? '',
            'bookCode'          => $POST['bookCode'] ?? ''
        ];

        return $bookInfo;
    }
}
