<?php
//Ver vuelos/reservas hechos y pendientes, crear reserva, editar los servicios contratados, crear nuevo vuelo

require_once './Controllers/BaseController.php';
require_once './Tools/SessionTool.php';
final class BookController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function storeFlightDetails(array $POST)
    {
        require_once './Sanitizers/FlightSanitizer.php';
        require_once './Validators/FlightValidator.php';

        $flightDetails = [
            'flightCode'      => $POST['flightCode'] ?? '',
            'adultsNumber'    => $POST['adultsNumber'] ?? '',
            'childsNumber'    => $POST['childsNumber'] ?? '',
            'infantsNumber'   => $POST['infantsNumber'] ?? '',
            'dateTime'        => date('Y-m-d H:i:s')
        ];

        $direction = $POST['direction'] ?? '';

        $flightDetails = FlightSanitizer::sanitize($flightDetails);
        if (!FlightValidator::validate($flightDetails)) {
            return false;
        }

        if (!$this->validateDirection($direction)) {
            return false;
        }

        //Comprobar si caben en el viaje
        SessionTool::set([$direction, 'flightDetails'], $flightDetails);
        return true;
    }

    public function storePassengerDetails(array $POST)
    {
        require_once './Sanitizers/PassengerSanitizer.php';
        require_once './Validators/PassengerValidator.php';
        require_once './Validators/PassengersValidator.php';

        $passengers = $POST['passengers'];
        $passengersDetails = [];

        //Vamos recogiendo los datos de los pasajeros
        foreach ($passengers as $passenger) {
            $passengerDetails = [];

            $keys_default = [
                'documentationType' => '',
                'documentCode' => '',
                'expirationDate' => '',
                'title' => null,
                'firstName' => '',
                'lastName' => '',
                'ageCategory' => '',
                'nationality' => '',
                'country' => '',
                'dateBirth' => null,
                'assistiveDevices' => null,
                'medicalEquipment' => null,
                'mobilityLimitations' => null,
                'communicationNeeds' => null,
                'medicationRequirements' => null,
                'services' => null
            ];

            foreach ($keys_default as $key => $defaultValue) {
                $passengerDetails[$key] = $passenger[$key] ?? $defaultValue;
            }

            //Cada pasajero lo saneamos y validamos
            $passengerDetails = PassengerSanitizer::sanitize($passengerDetails);
            if (!PassengerValidator::validate($passengerDetails)) {
                return false;
            }

            array_push($passengersDetails, $passengerDetails);
        }

        $direction = $POST['direction'] ?? '';


        if (!PassengersValidator::validate($passengersDetails)) {
            return false;
        }

        if (!$this->validateDirection($direction)) {
            return false;
        }

        //Si todo ha ido bien metemos en session a los pasajeros
        SessionTool::set([$direction, 'passengersDetails'], $passengersDetails);
        return true;
    }

    public function storeBookServicesDetails(array $POST)
    {
        //$POST será un array que contendrá códigos de servicios que solicita el cliente
        require_once './Sanitizers/BookServicesSanitizer.php';
        require_once './Validators/BookServicesValidator.php';


        $servicesDetails = [
            'SRV003' => null,
            'SRV004' => null,
            'SRV010' => null
        ];

        $direction = $POST['direction'] ?? '';
        foreach ($servicesDetails as $key => $defaultValue) {
            $servicesDetails[$key] = $POST[$key] ?? $defaultValue;
        }

        //Sanear
        //Validar si todos están en bbdd
        $servicesDetails = BookServicesSanitizer::sanitize($servicesDetails);
        if (!BookServicesValidator::validate($servicesDetails)) {
            return false;
        }

        if (!$this->validateDirection($direction)) {
            return false;
        }

        SessionTool::set([$direction, 'bookServicesDetails'], $servicesDetails);
        return true;
    }

    //se debe exige login en este paso
    public function storePrimaryContactInformationDetails(array $POST)
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
        ];

        foreach ($primaryContactDetails as $key => $defaultValue) {
            $primaryContactDetails[$key] = $POST[$key] ?? $defaultValue;
        }
        $direction = $POST['direction'] ?? '';

        require_once './Sanitizers/PrimaryContactInformationSanitizer.php';
        require_once './Validators/PrimaryContactInformationValidator.php';
        $primaryContactDetails = PrimaryContactInformationSanitizer::sanitize($primaryContactDetails);
        if (!PrimaryContactInformationValidator::validate($primaryContactDetails)) {
            return false;
        }

        if (!$this->validateDirection($direction)) {
            return false;
        }

        SessionTool::set([$direction, 'primaryContactDetails'], $primaryContactDetails);
        return true;
    }

    public function paymentDetails(array $POST)
    {
        require_once './Sanitizers/TokenSanitizer.php';
        require_once './Validators/TokenValidator.php';
        require_once './Tools/TokenTool.php';
        require_once './Tools/BookingPriceCalculatorTool.php';
        require_once './Tools/BookingDataEnricherTool.php';

        $BookingPriceCalculatorTool = new BookingPriceCalculatorTool();
        $BookingDataEnricherTool = new BookingDataEnricherTool();

        $accessToken = $POST['accessToken'];
        $accessToken = TokenSanitizer::sanitizeToken($accessToken);
        if (!TokenValidator::validateToken($accessToken)) {
            return false;
        }

        $decodedToken = TokenTool::decodeAndCheckToken($accessToken, 'access');

        if (!$decodedToken['response']) {
            return false;
        }

        if (!$this->checkDetails('departure')) {
            return false;
        }

        //Obtenemos lo que hemos guardado en sesiones pero con precios, y además, siendo precios actualizados
        $departureWithPrices = $BookingDataEnricherTool->getCompleteBookWithPricesFromSession('departure');
        $departureTotalPrice = $BookingPriceCalculatorTool->calculateTotalPriceFromBookWithPrices($departureWithPrices);

        if (!$this->saveBooking($departureWithPrices, $decodedToken['response']->data->id, 'departure', $departureTotalPrice)) {
            return false;
        }

        $returnWithPrices;
        $returnTotalPrice = 0;
        if (!is_null(SessionTool::get('return'))) {
            if (!$this->checkDetails('return')) {
                return false;
            }
            $returnWithPrices = $BookingDataEnricherTool->getCompleteBookWithPricesFromSession('return');
            $returnTotalPrice = $BookingPriceCalculatorTool->calculateTotalPriceFromBookWithPrices($returnWithPrices);
            if (!$this->saveBooking($returnWithPrices, $decodedToken['response']->data->id, 'return', $returnWithPrice)) {
                return false;
            }
        }
        $totalPrice = $departureTotalPrice + $returnTotalPrice;

        /*
        if (!$this->doPayment($totalPrice)) {
           return false;
        }
        */
        //Guardar factura
        //Enviar factura al email

        return true;
    }

    private function checkDetails($direction)
    {
        if (is_null(SessionTool::get($direction))) {
            return false;
        }

        $detailsToCheck = ['flightDetails', 'passengersDetails', 'bookServicesDetails', 'primaryContactDetails'];

        foreach ($detailsToCheck as $detail) {
            if (is_null(SessionTool::get([$direction, $detail]))) {
                return false;
            }
        }

        return true;
    }

    private function saveBooking(array $bookData, $idUser, $direction = 'departure', $totalPrice)
    {
        require_once './Tools/BookDataManipulatorTool.php';
        $BookDataManipulatorTool = new BookDataManipulatorTool();
        $flightModel = new FlightModel();
        $airplaneModel = new AirplaneModel();

        //Primero comprobamos si el número de asientos necesarios es menor que los disponibles
        $seatsNeeded = $BookDataManipulatorTool->getSeatsNeeded($bookData['passengersDetails']);
        $freeSeats = $flightModel->getFreeSeats($bookData['flightDetails']['flightCode']);
        if (is_null($freeSeats)) {
            $idAirplane = $flightModel->getIdAirplanes($bookData['flightDetails']['flightCode']);
            $freeSeats = $airplaneModel->getSeats($idAirplane);
        }

        if ($seatsNeeded > $freeSeats) {
            return false;
        }

        $PrimaryContactInformationModel = new PrimaryContactInformationModel();
        $BookModel = new BookModel();
        $bookServiceModel = new BookServiceModel();
        $passengerModel = new PassengerModel();
        $additionalInformationModel = new AdditionalInformationModel();
        $passengerBookServiceModel = new PassengerBookServiceModel();
        $servicesModel = new ServicesModel();
        $invoiceModel = new InvoiceModel();
        $servicesInvoicesModel = new ServicesInvoicesModel();

        try {
            $PrimaryContactInformationModel->beginTransaction();

            //insertar el primaryContactInformation
            [$idPrimaryContactInfo] = $PrimaryContactInformationModel->createPrimaryContactInformation($bookData['primaryContactDetails'], true);
            if (!$idPrimaryContactInfo) {
                throw new Exception('Catched exception creating primary Contact Information');
            }

            //insertar el book
            $countsAgeCategories = $BookDataManipulatorTool->getPassengersNumberByAgeCategory($bookData['passengersDetails']);
            [$idBook] = $BookModel->createBooks([
                'id_FLIGHTS' => $flightModel->getIdFlightFromFlightCode($bookData['flightDetails']['flightCode']),
                'id_USERS' => $idUser,
                'id_PRIMARY_CONTACT_INFORMATIONS' => $idPrimaryContactInfo,
                'bookCode' => $BookDataManipulatorTool->generateUUID(),
                'direction' => $direction,
                'adultsNumber' => $countsAgeCategories['adult'],
                'childsNumber' => $countsAgeCategories['child'],
                'infantsNumber' => $countsAgeCategories['infant']
            ], true);
            if (!$idBook) {
                throw new Exception('Catched exception creating book');
            }

            //insertar Invoice
            [$idInvoice] = $invoiceModel->createInvoices([
                'id_BOOKS' => $idBook,
                'invoiceCode' => $BookDataManipulatorTool->generateUUID(),
                'invoicedDate' => date('Y-m-d H:i:s'),
                'price' => $totalPrice
            ], true);
            if (!$idInvoice) {
                throw new Exception('Catched exception creating invoice');
            }

            //insertar books_services
            //insertar collective services_invoice
            if (isset($bookData['bookServicesDetails']) && count($bookData['bookServicesDetails']) > 0) {
                $bookServices = [];
                $individualServicesInvoices = [];

                $serviceIdsFromCodes = $servicesModel->getServiceIdsFromCodes(array_keys($bookData['bookServicesDetails']));
                foreach ($bookData['bookServicesDetails'] as $serviceCode => $price) {
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

            $additionalInformationData = [];
            $passengerServiceData = [];
            $servicesInvoicesData = [];

            //insertar pasajeros + aditional_information
            foreach ($bookData['passengersDetails'] as $passenger) {
                [$idPassenger] = $passengerModel->createPassengers([
                    'passengerCode' => $BookDataManipulatorTool->generateUUID(),
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

                //insertar passengers_books_services
                $idsServices = $servicesModel->getServiceIdsFromCodes(array_keys($passenger['services']));
                foreach ($passenger['services'] as $serviceCode => $price) {
                    $passengerServiceData[] = [
                        'id_PASSENGERS' => $idPassenger,
                        'id_BOOKS' => $idBook,
                        'id_SERVICES' => $idsServices[$serviceCode]
                    ];

                    //insertar individual services_invoice
                    $servicesInvoicesData[] = [
                        'id_INVOICES' => $idInvoice,
                        'id_SERVICES' => $idsServices[$serviceCode],
                        'id_PASSENGERS' => $idPassenger,
                        'addRemove' => 'add',
                        'oldPrice' => $price,
                    ];
                }
            }

            $additionalInformationRsp = $additionalInformationModel->createMultipleAdditionalInformations($additionalInformationData);
            if (!$additionalInformationRsp) {
                throw new Exception('Catched exception additional Information');
            }

            $passengerBookServiceRsp = $passengerBookServiceModel->createMultiplePassengerService($passengerServiceData);
            if (!$passengerBookServiceRsp) {
                throw new Exception('Catched exception creating passenger book services');
            }

            $servicesInvoicesRsp = $servicesInvoicesModel->createMultipleServicesInvoices($servicesInvoicesData);
            if (!$servicesInvoicesRsp) {
                throw new Exception('Catched exception creating service invoices');
            }

            $PrimaryContactInformationModel->commit();
        } catch (Exception $e) {
            $PrimaryContactInformationModel->rollBack();
            error_log("He pasado por el rollBack");
            error_log($e);
            return false;
        }
        //actualizar freeSeats de flights
        error_log(print_r($bookData, true));
        return true;
    }

    private function doPayment($totalPrice)
    {
        //Proceso de pago
        require_once './Tools/PaymentTool.php';
        require_once './Tools/IniTool.php';
        $PaymentTool = new PaymentTool();
        $iniTool = new IniTool('./Config/cfg.ini');
        $paypal = $iniTool->getKeysAndValues('paypal');
        //calcular precio aquí para enviarselo a createPaymentPaypal()

        //Metemos los datos de factura en la bbdd en la tabla INVOICES (Añadir campos de los gastos)
        //Generamos token de vida corta (con el id del usuario y el de la factura) para mandarlo por get al returnUrl (allí se descodifica y se muestra la factura además de enviar por email)
        //OJO: si caduca el token, en el returnUrl se le debe avisar al cliente de que si quiere que se le expida la factura, se loguee y lo solicite en "Mis reservas".
        //El cancelUrl solo contendrá un aviso de que se canceló
        $PaymentTool->createPaymentPaypal($paypal['clientId'], $paypal['clientSecret'], $totalPrice, 'EUR', $paypal['returnUrl'], $paypal['cancelUrl']);
        //Aquí solo devolvemos true para que en el frontend ponga un modal con un botón para comprobar si se hizo la compra
        //si sí, avisa de ello, va al index.html y elimina las variables de session del viaje
        //si no, dará opción de volver a intentar compra (simplemente desaparece el modal, y se comprueba si siguen las variables de session y el login, (si no están pues al index y eliminar variables de session)), o de ir al index.html (entonces se eliminan las variables de session del viaje)
        return true;
    }

    //Para obtener la tarjeta de embarque, solo se puede hacer desde 24 a 48 hrs antes del vuelo
    public function confirmCheckin(array $POST)
    {
        //Checkin
        //solo se puede hacer desde 24 a 48 horas antes del vuelo
        //se confirma asistencia o se cancela
        //se obtiene tarjeta de embarque (que no es lo mismo que la factura)
        //si el cliente tiene una reserva, se le notifica por mail y sms (sms se considera servicio con precio)
        //se podrá seleccionar asiento si se pagó ese servicio
    }

    //Recibe id del usuario y devuelve un array o un JSON de los books
    public function getBooks(array $POST)
    {
    }

    //Recibirá un array de entidades y se aplicarán
    public function editBook()
    {
    }

    private function validateDirection($direction)
    {
        $direction = htmlspecialchars(trim($direction));
        if ($direction !== 'departure' && $direction !== 'return') {
            return false;
        }
        return true;
    }
}
