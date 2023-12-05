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
            'infantsNumber'   => $POST['infantsNumber'] ?? ''
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
                //$passengerDetails[$key] = (isset($passenger[$key]) && $passenger[$key] !== '') ? $passenger[$key] : $defaultValue;
                $passengerDetails[$key] = $passenger[$key] === null || $passenger[$key] === "" ? $defaultValue : $passenger[$key];
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

        //No ponemos aquí las variables admitidas porque todas son opcionales (valor por defecto null), y
        // porque en el validate ya se hace una solicitud a BBDD para comprobar las key y value que se envíen,
        // así evitamos una llamada extra a BBDD

        $servicesDetails = $POST['bookServices'] ?? [];
        $direction = $POST['direction'] ?? '';

        if (!$this->validateDirection($direction)) {
            return false;
        }

        if (is_array($servicesDetails) && count($servicesDetails) > 0) {
            //Sanear
            //Validar si todos están en bbdd
            $servicesDetails = BookServicesSanitizer::sanitize($servicesDetails);
            if (!BookServicesValidator::validate($servicesDetails)) {
                return false;
            }
        }
        SessionTool::set([$direction, 'bookServicesDetails'], $servicesDetails);
        return true;
    }

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
            //$primaryContactDetails[$key] = (isset($primaryContactDetails[$key]) && $primaryContactDetails[$key] !== '') ? $primaryContactDetails[$key] : $defaultValue;
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
        require_once './Tools/IniTool.php';

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

        $idUser = $decodedToken['response']->data->id;

        //Obtenemos lo que hemos guardado en sesiones pero con precios, y además, siendo precios actualizados
        $departureWithPrices = $BookingDataEnricherTool->getCompleteBookWithPricesFromSession('departure');
        $departureTotalPrice = $BookingPriceCalculatorTool->calculateTotalPriceFromBookWithPrices($departureWithPrices);

        $idInvoiceD = $this->saveBooking($departureWithPrices, $idUser, 'departure', $departureTotalPrice);
        if (!$idInvoiceD) {
            return false;
        }

        $returnWithPrices;
        $returnTotalPrice = 0;
        $idInvoiceR = null;
        if (!is_null(SessionTool::get('return'))) {
            if (!$this->checkDetails('return')) {
                return false;
            }
            $returnWithPrices = $BookingDataEnricherTool->getCompleteBookWithPricesFromSession('return');
            $returnTotalPrice = $BookingPriceCalculatorTool->calculateTotalPriceFromBookWithPrices($returnWithPrices);
            $idInvoiceR = $this->saveBooking($returnWithPrices, $idUser, 'return', $returnTotalPrice);
            if (!$idInvoiceR) {
                return false;
            }
        }

        if (!$this->doPayment($totalPrice,  $idUser, $idInvoiceD, $idInvoiceR)) {
            return false;
        }

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
        require_once './Tools/IniTool.php';
        require_once './Tools/BookingProcessTool.php';
        $BookingProcessTool = new BookingProcessTool();
        $flightModel = new FlightModel();
        $iniTool = new IniTool('./Config/cfg.ini');
        $priceSettings = $iniTool->getKeysAndValues('priceSettings');

        if (!$BookingProcessTool->validateSeatAvailability($bookData['passengersDetails'], $bookData['flightDetails']['flightCode'])) {
            return false;
        }

        try {
            $flightModel->beginTransaction();

            //insertar el primaryContactInformation
            $idPrimaryContactInfo = $BookingProcessTool->savePrimaryContactInfo($bookData['primaryContactDetails']);

            //insertar el book
            $idBook = $BookingProcessTool->saveBook($bookData['passengersDetails'], $bookData['flightDetails']['flightCode'], $direction, $idPrimaryContactInfo, $idUser);

            //insertar Invoice
            $idInvoice = $BookingProcessTool->saveInvoice($idBook, $totalPrice);

            //insertar books_services
            //insertar collective services_invoice
            if (isset($bookData['bookServicesDetails']) && is_array($bookData['bookServicesDetails']) && count($bookData['bookServicesDetails']) > 0) {
                $BookingProcessTool->saveBookServicesAndServicesInvoices($idBook, $bookData['bookServicesDetails'], $idInvoice);
            }

            //insertar pasajeros + aditional_information
            [$additionalInformationData, $passengerServiceData, $servicesInvoicesData] = $BookingProcessTool->savePassengersAndGetAddiInfoAndPassServAndServInvo($bookData['passengersDetails'], $idBook, $idInvoice);
            $BookingProcessTool->createAdditionalInformation($additionalInformationData);
            $BookingProcessTool->createPassengerBookService($passengerServiceData);
            $BookingProcessTool->createServicesInvoices($servicesInvoicesData);

            //Discounts
            //Almacenar descuento de más de x pasajeros
            $servicesModel = new ServicesModel();
            $databaseFieldMappings = $iniTool->getKeysAndValues("databaseFieldMappings");
            $discounts = [];
            if (count($bookData['passengersDetails']) > intval($priceSettings['discountForMoreThanXPersons'])) {
                $discountForMoreThanXPersonsCode = $databaseFieldMappings['discountForMoreThanXPersonsCode'];
                $discountForMoreThanXPersonsPrice = $servicesModel->readServicePrice($discountForMoreThanXPersonsCode);
                $discounts[$discountForMoreThanXPersonsCode] = $discountForMoreThanXPersonsPrice;
            }

            //Almacenar descuento de vuelta
            if ($direction === 'return') {
                $discountReturnCode = $databaseFieldMappings['discountForMoreThanXPersonsCode'];
                $discountReturnPrice = $servicesModel->readServicePrice($discountReturnCode);
                $discounts[$discountReturnCode] = $discountReturnPrice;
            }

            //Guardamos descuentos en BBDD
            if (!empty($discounts)) {
                $BookingProcessTool->saveBookServicesAndServicesInvoices($idBook, $discounts, $idInvoice);
            }

            //Volvemos a revisar si siguen habiendo libres y actualizamos freeSeats del vuelo correspondiente
            $BookingProcessTool->decreaseAvailableSeats($bookData['passengersDetails'], $bookData['flightDetails']['flightCode']);

            $flightModel->commit();
        } catch (Exception $e) {
            $flightModel->rollBack();
            error_log($e);
            return false;
        }

        return $idInvoice;
    }

    private function doPayment($totalPrice, $idUser, $idInvoiceD, $idInvoiceR = null)
    {
        require_once './Tools/PaymentTool.php';
        require_once './Tools/TokenTool.php';
        require_once './Tools/IniTool.php';
        $PaymentTool = new PaymentTool();
        $iniTool = new IniTool('./Config/cfg.ini');
        $paypalCfg = $iniTool->getKeysAndValues('paypal');
        $projectSettings = $iniTool->getKeysAndValues('projectSettings');

        //CREAR TOKEN de 3 horas (caducidad de paypal en su web)
        $data = ['id' => $idUser, 'idUser' => $idUser, 'idInvoiceD' => $idInvoiceD, 'type' => 'paypalredirectok'];
        if ($idInvoiceR) {
            $data['idInvoiceR'] = $idInvoiceR;
        }
        $paymentToken = TokenTool::generateToken($data, intval($paypalCfg['secondsTimeLifePaymentReturnUrl']));

        //En el cfg.ini se puede configurar, si se desea, probar el proyecto sin el proceso de paypal
        if ($projectSettings['doPaypalProccess'] === '0') {
            header('Location: ' . $paypalCfg['returnUrl'] . '?token=' . $paymentToken . '&command=paypalredirectok');
        } else {
            $PaymentTool->createPaymentPaypal($paypalCfg['clientId'], $paypalCfg['clientSecret'], $totalPrice, 'EUR', $paypalCfg['returnUrl'] . '?token=' . $paymentToken . '&command=paypalredirectok', $paypalCfg['cancelUrl'] . '&command=paypalredirectcancel');
        }

        //Aquí solo devolvemos true para que en el frontend ponga un modal con un botón para comprobar si se hizo la compra
        //si sí, avisa de ello, va al index.html y elimina las variables de session del viaje
        //si no, dará opción de volver a intentar compra (simplemente desaparece el modal, y se comprueba si siguen las variables de session y el login, (si no están pues al index y eliminar variables de session)), o de ir al index.html (entonces se eliminan las variables de session del viaje)
        return true;
    }

    //Para obtener la tarjeta de embarque, solo se puede hacer desde 24 a 48 hrs antes del vuelo
    public function checkin(array $POST)
    {
        require_once './Sanitizers/CheckinSanitizer.php';
        require_once './Validators/CheckinValidator.php';
        require_once './Sanitizers/TokenSanitizer.php';
        require_once './Validators/TokenValidator.php';
        require_once './Tools/CheckinProcessTool.php';
        require_once './Tools/TokenTool.php';

        $checkinDetails = [
            'accessToken'       => $POST['accessToken'] ?? '',
            'bookCode'          => $POST['bookCode'] ?? ''
        ];

        $accessToken = $POST['accessToken'];
        $accessToken = TokenSanitizer::sanitizeToken($accessToken);
        if (!TokenValidator::validateToken($accessToken)) {
            return false;
        }

        $checkinDetails = CheckinSanitizer::sanitize($checkinDetails);
        if (!CheckinValidator::validate($checkinDetails)) {
            return false;
        }

        $decodedToken = TokenTool::decodeAndCheckToken($accessToken, 'access');

        if (!$decodedToken['response']) {
            return false;
        }
        $bookCode = $checkinDetails['bookCode'];
        $idUser = $decodedToken['response']->data->id;

        //Obtener el flightId en tabla book con el bookCode, el idUser, con checkin a null
        $bookModel = new BookModel();
        $idFlight = $bookModel->readFlightId($bookCode, $idUser);
        if (!$idFlight) {
            return false;
        }

        //en la tabla flights, con el flightId obtener la fecha y hora del vuelo
        $flightModel = new FlightModel();
        $dateHour = $flightModel->getFlightDateHourFromIdFlight($idFlight);
        $flightDate = $dateHour['date'];
        $flightHour = $dateHour['hour'];

        $checkinProcessTool = new CheckinProcessTool();
        $isPastDateTime = $checkinProcessTool->isPastDateTime($flightDate, $flightHour);
        $hoursDifference = $checkinProcessTool->getHoursDifference($flightDate, $flightHour);

        //Comprobar si faltan menos de 48 hrs para el vuelo
        if (!$isPastDateTime || $hoursDifference > 48) {
            return false;
        }

        //poner la fecha actual
        if(!$bookModel->updateChecking($bookCode)) {
            return false;
        }

        //generar tarjetas de embarque y enviarlas al email
        return true;

        //Avisos automáticos del checkin desde backend
        //si el cliente tiene una reserva y entra en las 48 hrs de antelación al vuelo, se le notifica por mail y sms
        //EXTRA: se podrá seleccionar asiento si se pagó ese servicio
    }

    public function getBooks(array $POST)
    {
        //Recibe id del usuario y accessToken, y devuelve un array o un JSON de los books
    }

    public function editBook()
    {
        //Recibirá un array de datos, un accessToken y se aplicarán
    }

    private function validateDirection($direction)
    {
        $direction = htmlspecialchars(trim($direction));
        if ($direction !== 'departure' && $direction !== 'return') {
            return false;
        }
        return true;
    }

    public function cancelBooking()
    {
        SessionTool::remove('departure');
        SessionTool::remove('return');
        return true;
    }
}
