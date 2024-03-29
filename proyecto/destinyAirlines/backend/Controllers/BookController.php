<?php
//Ver vuelos/reservas hechas y pendientes, crear reserva, crear nuevo vuelo

require_once ROOT_PATH . '/Controllers/BaseController.php';

final class BookController extends BaseController
{

    public function __construct()
    {
        parent::__construct();
        parent::loadFilter('Book');
    }

    public function storeFlightDetails(array $POST): bool
    {
        $flightDetails = $this->filter->filterFlightData($POST);

        $flightDetails = $this->processData->processData($flightDetails, 'Flight');
        if (!$flightDetails) {
            return false;
        }

        $direction = $POST['direction'] ?? '';

        if (!$this->validateDirection($direction)) {
            return false;
        }

        //Comprobar si caben en el viaje
        SessionTool::set([$direction, 'flightDetails'], $flightDetails);
        return true;
    }

    public function storePassengerDetails(array $POST): bool
    {
        $passengersDetails = $this->filter->filterPassengersData($POST);

        $direction = $POST['direction'] ?? '';

        $passengersDetails = $this->processData->processData($passengersDetails, 'Passengers');
        if (!$passengersDetails) {
            return false;
        }

        if (!$this->validateDirection($direction)) {
            return false;
        }

        //Si todo ha ido bien metemos en session a los pasajeros
        SessionTool::set([$direction, 'passengersDetails'], $passengersDetails);
        return true;
    }

    public function storeBookServicesDetails(array $POST): bool
    {
        //$POST será un array que contendrá códigos de servicios que solicita el cliente

        $NestedPOST = $this->filter->filterBookServicesData($POST);

        $servicesDetails = $NestedPOST['bookServices'] ?? [];
        $direction = $NestedPOST['direction'] ?? '';

        if (!$this->validateDirection($direction)) {
            return false;
        }

        if (is_array($servicesDetails) && count($servicesDetails) > 0) {
            $servicesDetails = $this->processData->processData($servicesDetails, 'BookServices');
            if (!$servicesDetails) {
                return false;
            }
        }
        SessionTool::set([$direction, 'bookServicesDetails'], $servicesDetails);
        return true;
    }

    public function storePrimaryContactInformationDetails(array $POST): bool
    {
        $primaryContactDetails = $this->filter->filterPrimaryContactInformationData($POST);

        $direction = $POST['direction'] ?? '';

        $primaryContactDetails = $this->processData->processData($primaryContactDetails, 'PrimaryContactInformation');
        if (!$primaryContactDetails) {
            return false;
        }

        if (!$this->validateDirection($direction)) {
            return false;
        }

        SessionTool::set([$direction, 'primaryContactDetails'], $primaryContactDetails);
        return true;
    }

    public function paymentDetails(array $POST): bool
    {
        $BookingPriceCalculatorTool = new BookingPriceCalculatorTool();
        $BookingDataEnricherTool = new BookingDataEnricherTool();

        $accessToken = $POST['accessToken'];

        $accessToken = $this->processData->processData(['accessToken' => $accessToken], 'Token');
        if (!$accessToken['accessToken']) {
            return false;
        }
        $accessToken = $accessToken['accessToken'];

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

        $returnWithPrices = null;
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

        $totalPrice = $departureTotalPrice + $returnTotalPrice;
        if (!$this->doPayment($totalPrice,  $idUser, $idInvoiceD, $idInvoiceR)) {
            return false;
        }

        return true;
    }

    private function checkDetails(string $direction): bool
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

    private function saveBooking(array $bookData, string|int $idUser, string $direction = 'departure', float $totalPrice): bool|string
    {
        $BookingProcessTool = new BookingProcessTool();
        $flightModel = new FlightModel();
        $priceSettings = $this->iniTool->getKeysAndValues('priceSettings');

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
            if (!empty($passengerServiceData)) {
                $BookingProcessTool->createPassengerBookService($passengerServiceData);
            }
            if (!empty($servicesInvoicesData)) {
                $BookingProcessTool->createServicesInvoices($servicesInvoicesData);
            }

            //Discounts
            //Almacenar descuento de más de x pasajeros
            $servicesModel = new ServicesModel();
            $databaseFieldMappings = $this->iniTool->getKeysAndValues("databaseFieldMappings");
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

    private function doPayment(float $totalPrice, string|int $idUser, string|int $idInvoiceD, string|int $idInvoiceR = null): bool
    {
        $PaymentTool = new PaymentTool();
        $paypalCfg = $this->iniTool->getKeysAndValues('paypal');
        $tokenSettings = $this->iniTool->getKeysAndValues('tokenSettings');
        $projectSettings = $this->iniTool->getKeysAndValues('projectSettings');

        //CREAR TOKEN de 3 horas (caducidad de paypal en su web)
        $data = ['id' => $idUser, 'idUser' => $idUser, 'idInvoiceD' => $idInvoiceD, 'type' => 'paypalredirectok'];
        if ($idInvoiceR) {
            $data['idInvoiceR'] = $idInvoiceR;
        }
        $paymentToken = TokenTool::generateToken($data, intval($tokenSettings['secondsTimeLifePaymentReturnUrl']));

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
    public function checkin(array $POST): bool
    {
        $checkinDetails = $this->filter->filterCheckinData($POST);

        $accessToken = $POST['accessToken'];

        $accessToken = $this->processData->processData(['accessToken' => $accessToken], 'Token');
        if (!$accessToken['accessToken']) {
            return false;
        }
        $accessToken = $accessToken['accessToken'];

        $checkinDetails = $this->processData->processData($checkinDetails, 'Checkin');
        if (!$checkinDetails) {
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
        $idFlight = $bookModel->readFlightIdWithCheckinNull($bookCode, $idUser);
        if (!$idFlight) {
            return false;
        }

        //en la tabla flights, con el flightId obtener la fecha y hora del vuelo
        $flightModel = new FlightModel();
        $flightData = $flightModel->getFlightDateHourIdItineraryFlightCodeFromIdFlight($idFlight);
        $flightDate = $flightData['date'];
        $flightHour = $flightData['hour'];
        $idItinerary = $flightData['id_ITINERARIES'];
        $flightCode = $flightData['flightCode'];

        $checkinSettings = $this->iniTool->getKeysAndValues('checkinSettings');
        $timeTool = new TimeTool();
        $isPastDateTime = $timeTool->isPastDateTime($flightDate, $flightHour);
        $hoursDifference = $timeTool->getHoursDifference($flightDate, $flightHour);
        if (!$isPastDateTime || $hoursDifference > intval($checkinSettings['maximumAdvanceHoursForCheckIn'])) {
            return false;
        }

        //generar tarjetas de embarque y enviarlas al email
        $checkinTool = new CheckinTool();
        $pdfTool = new PdfTool();
        $emailTool = new EmailTool();
        $userModel = new UserModel();

        $checkinData = $checkinTool->generateCheckinData(['bookCode' => $bookCode, 'flightDate' => $flightDate, 'flightHour' => $flightHour, 'idItinerary' => $idItinerary, 'flightCode' => $flightCode]);
        if (!$checkinData) {
            return false;
        }

        $boardingPassHtml = $checkinTool->generateBoardingPassHtml($checkinData);

        $boardingPassPdf = $pdfTool->generatePdfFromHtml($boardingPassHtml, false);

        $cfgOriginEmailIni = $this->iniTool->getKeysAndValues("originEmail");
        $subject = 'Tarjetas de embarque para su viaje';
        $message = '¡Gracias por elegir volar con Destiny Airlines!.

        Adjuntamos a este correo electrónico las tarjetas de embarque de su viaje. Le recomendamos que la guarde para sus registros.
        
        Si tiene alguna pregunta o necesita más información, no dude en ponerse en contacto con nosotros.
        
        ¡Esperamos verle a bordo pronto!
        
        Saludos cordiales,
        El equipo de Destiny Airlines';

        $emailUser = $userModel->getEmailById($idUser);
        $emailTool->sendEmail(
            [
                'toEmail' => $emailUser,
                'fromEmail' => $cfgOriginEmailIni['email'],
                'fromPassword' => $cfgOriginEmailIni['password'],
                'subject' => $subject,
                'message' => $message
            ],
            'boardingPassTemplate',
            $boardingPassPdf,
            'boardingCard'
        );

        //poner la fecha actual
        if (!$bookModel->updateChecking($bookCode)) {
            return false;
        }

        return true;
    }

    public function getSummaryBooks(array $POST): bool|array
    {
        //Recibe id del usuario y accessToken, y devuelve un array o un JSON de los books

        $accessToken = $POST['accessToken'];
        $accessToken = $this->processData->processData(['accessToken' => $accessToken], 'Token');
        if (!$accessToken['accessToken']) {
            return false;
        }
        $accessToken = $accessToken['accessToken'];

        $decodedToken = TokenTool::decodeAndCheckToken($accessToken, 'access');

        if (!$decodedToken['response']) {
            return false;
        }

        $idUser = $decodedToken['response']->data->id;

        $multiModel = new MultiModel();

        return $multiModel->getSummaryBooks($idUser);
    }

    public function getBookInfo(array $POST): bool|array
    {
        //Recibe id del usuario y accessToken, y devuelve un array o un JSON de los books
        $bookInfo = $this->filter->filterBookInfoData($POST);

        $tokenSanitized = $this->processData->processData(['accessToken' => $bookInfo['accessToken']], 'Token');
        if (!$tokenSanitized['accessToken']) {
            return false;
        }
        $tokenSanitized = $tokenSanitized['accessToken'];

        $bookInfo['accessToken'] = $tokenSanitized;

        $decodedToken = TokenTool::decodeAndCheckToken($tokenSanitized, 'access');

        if (!$decodedToken['response']) {
            return false;
        }

        $bookInfo = $this->processData->processData($bookInfo, 'BookInfo');
        if (!$bookInfo) {
            return false;
        }

        $idUser = $decodedToken['response']->data->id;
        $bookCode = $bookInfo['bookCode'];

        $multiModel = new MultiModel();

        return $multiModel->getBookInfo($bookCode, $idUser);
    }

    public function cancelBook(array $POST): bool
    {
        $bookInfo = $this->filter->filterCancelBookData($POST);
        $tokenSanitized = $this->processData->processData(['accessToken' => $bookInfo['accessToken']], 'Token');
        if (!$tokenSanitized['accessToken']) {
            return false;
        }
        $tokenSanitized = $tokenSanitized['accessToken'];

        $bookInfo['accessToken'] = $tokenSanitized;

        $decodedToken = TokenTool::decodeAndCheckToken($tokenSanitized, 'access');

        if (!$decodedToken['response']) {
            return false;
        }

        $bookInfo = $this->processData->processData($bookInfo, 'BookInfo');
        if (!$bookInfo) {
            return false;
        }

        $idUser = $decodedToken['response']->data->id;
        $bookCode = $bookInfo['bookCode'];

        $bookModel = new BookModel();
        $flightModel = new FlightModel();
        $timeTool = new TimeTool();

        if (!$bookModel->checkBookCodeWithIdUser($bookCode, $idUser)) {
            return false;
        }

        //Obtener el flightId en tabla book con el bookCode, el idUser
        $idFlight = $bookModel->readFlightId($bookCode, $idUser);
        if (!$idFlight) {
            return false;
        }

        //en la tabla flights, con el flightId obtener la fecha y hora del vuelo
        $flightData = $flightModel->getFlightDateHourFromIdFlight($idFlight);
        $flightDate = $flightData['date'];
        $flightHour = $flightData['hour'];

        $bookSettings = $this->iniTool->getKeysAndValues('bookSettings');
        $isPastDateTime = $timeTool->isPastDateTime($flightDate, $flightHour);
        $hoursDifference = $timeTool->getHoursDifference($flightDate, $flightHour);
        if ($isPastDateTime || $hoursDifference < intval($bookSettings['maxAdvantaceHoursForCancelBook'])) {
            return false;
        }

        $bookModel = new BookModel();
        //Si el vuelo no ha tenido lugar, se suman los asientos disponibles gracias al trigger sql
        return $bookModel->deleteBookFromBookCode($bookCode);
    }

    private function validateDirection(string $direction): bool
    {
        $direction = htmlspecialchars(trim($direction));
        if ($direction !== 'departure' && $direction !== 'return') {
            return false;
        }
        return true;
    }

    public function cancelBooking(): bool
    {
        SessionTool::remove('departure');
        SessionTool::remove('return');
        return true;
    }
}
