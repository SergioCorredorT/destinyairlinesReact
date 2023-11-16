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

        $accessToken = $POST['accessToken'];
        $accessToken = TokenSanitizer::sanitizeToken($accessToken);
        if (!TokenValidator::validateToken($accessToken)) {
            return false;
        }
        //Comprobar accessToken aquí

        $decodedToken = TokenTool::decodeAndCheckToken($accessToken, 'access');

        if (!$decodedToken['response']) {
            return false;
        }

        //En vez de generar precio total, generar objeto factura y devuelva un array asociativo con precios y un total
        $departureTotalPrice = $this->generateTotalPrice('departure');
        $returnTotalPrice = 0;
        if (!is_null(SessionTool::get('return'))) {
            $returnTotalPrice = $this->generateTotalPrice('return');
        }

        if (!$this->saveBooking()) {
            return false;
        }

        /*if (!$this->doPayment($totalPrice)) {
           return false;
        }*/
        //Guardar factura

        return true;
    }

    private function generateTotalPrice($direction)
    {
        $allSessions = SessionTool::getAll();
        //$primaryContactDetails = $allSessions[$direction]['primaryContactDetails'];

        $passengersTotalPrice = $this->calculatePassengersPrice($allSessions[$direction]);
        $bookServicesTotalPrice = $this->calculateBookServicesPrice($allSessions[$direction]);
        //Comprobar si caben en el viaje

        error_log("Sesiones $direction:" . print_r($allSessions, true));
        error_log("Passengers Total Price $direction: " . print_r($passengersTotalPrice, true));
        error_log("Book services Total Price $direction: " . print_r($bookServicesTotalPrice, true));

        return $passengersTotalPrice + $bookServicesTotalPrice;
    }

    private function calculatePassengersPrice($allSessionsInDirection)
    {
        require_once './Tools/IniTool.php';

        $flightDetails = $allSessionsInDirection['flightDetails'];
        $passengersDetails = $allSessionsInDirection['passengersDetails'];

        $iniTool = new IniTool('./Config/cfg.ini');
        $flightModel = new FlightModel();
        $servicesModel = new ServicesModel();

        $priceSettings = $iniTool->getKeysAndValues("priceSettings");
        $childDiscountPercentage = intval($priceSettings['childDiscountPercentage']);
        $infantDiscountPercentage = intval($priceSettings['infantDiscountPercentage']);
        $discountForMoreThanXPersons = intval($priceSettings['discountForMoreThanXPersons']);
        if (!$discountForMoreThanXPersons) {
            $discountForMoreThanXPersons = 0;
        }

        //Calculate flight price
        $flightPrice = $flightModel->getFlightPrice($flightDetails['flightCode']);

        $discountedPrices = [
            'adult'  => $flightPrice,
            'child'  => $flightPrice * (1 - ($childDiscountPercentage / 100)),
            'infant' => $flightPrice * (1 - ($infantDiscountPercentage / 100))
        ];
        $passengerCount = ['adult' => 0, 'child' => 0, 'infant' => 0];

        //Calculate passengers price
        $serviceCodes = [];
        foreach ($passengersDetails as $passenger) {
            $passengerCount[$passenger['ageCategory']]++;
            //Recogemos todos los servicios individuales contratados sin repetir de todos los pasajeros
            if (!empty($passenger['services'])) {
                foreach ($passenger['services'] as $serviceCode) {
                    $serviceCodes[$serviceCode] = $serviceCode;
                }
            }
        }

        $passengerServicesPrice = 0.0;
        if (!empty($passenger['services'])) {
            //Recogemos los precios de la variedad de servicios de nuestros pasajeros
            $servicesWithPrices = $servicesModel->readServicePrices($serviceCodes);
            //Sumamos todos los precios de los servicios individuales
            foreach ($passengersDetails as $passenger) {
                foreach ($passenger['services'] as $serviceCode) {
                    $passengerServicesPrice += $servicesWithPrices[$serviceCode];
                }
            }
        }
        $passengerFlightPrice = $passengerCount['adult'] * $discountedPrices['adult'] + $passengerCount['child'] * $discountedPrices['child'] + $passengerCount['infant'] * $discountedPrices['infant'];
        $passengersTotalPrice = $passengerFlightPrice + $passengerServicesPrice;

        //Aplicamos el descuento de si supera X personas y no está configurado a 0 personas
        if ($passengerCount['adult'] + $passengerCount['child'] + $passengerCount['infant'] > $discountForMoreThanXPersons && $discountForMoreThanXPersons > 0) {
            $discountPercentage = $servicesModel->readServiceDiscount('SRV009');
            if ($discountPercentage) {
                $passengersTotalPrice = $passengersTotalPrice - ($passengersTotalPrice * ($discountPercentage / 100));
            }
        }
        return $passengersTotalPrice;
    }

    private function calculateBookServicesPrice($allSessionsInDirection)
    {
        $bookServicesDetails = $allSessionsInDirection['bookServicesDetails'];
        $bookServicesTotalPrice = 0;
        if (!empty($bookServicesDetails)) {
            $servicesModel = new ServicesModel();
            $servicesWithPrices = $servicesModel->readServicePrices($bookServicesDetails);
            $bookServicesTotalPrice = 0.0;

            foreach ($servicesWithPrices as $price) {
                $bookServicesTotalPrice += $price;
            }
        }
        return $bookServicesTotalPrice;
    }

    private function doPayment($totalPrice)
    {
        //Proceso de pago
        require_once './Tools/PaymentTool.php';
        require_once './Tools/IniTool.php';
        $PaymentTool = new PaymentTool();
        $iniTool = new IniTool('./Config/cfg.ini');
        $paypal = $iniTool->getKeysAndValues("paypal");
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

    private function saveBooking()
    {
        $BookModel = new BookModel();
        //Recoger todos los datos de la session y guardar el book
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
