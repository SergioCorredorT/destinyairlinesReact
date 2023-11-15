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

        $flightDetails = FlightSanitizer::sanitize($flightDetails);
        $isValidate = FlightValidator::validate($flightDetails);
        if (!$isValidate) {
            return false;
        }
        //Comprobar si caben en el viaje
        SessionTool::set('flightDetails', $flightDetails);
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
            $isValidate = PassengerValidator::validate($passengerDetails);
            if (!$isValidate) {
                return false;
            }

            array_push($passengersDetails, $passengerDetails);
        }

        if(!PassengersValidator::validate($passengersDetails))
        {
            return false;
        }

        //Si todo ha ido bien metemos en session a los pasajeros
        SessionTool::set('passengersDetails', $passengersDetails);
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

        foreach ($servicesDetails as $key => $defaultValue) {
            $servicesDetails[$key] = $POST[$key] ?? $defaultValue;
        }

        //Sanear
        //Validar si todos están en bbdd
        $servicesDetails = BookServicesSanitizer::sanitize($servicesDetails);
        $isValidate      = BookServicesValidator::validate($servicesDetails);

        if (!$isValidate) {
            return false;
        }
        SessionTool::set('bookServicesDetails', $servicesDetails);
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
            'companyPhoneNumber' => null
        ];

        foreach ($primaryContactDetails as $key => $defaultValue) {
            $primaryContactDetails[$key] = $POST[$key] ?? $defaultValue;
        }

        require_once './Sanitizers/PrimaryContactInformationSanitizer.php';
        require_once './Validators/PrimaryContactInformationValidator.php';
        $primaryContactDetails = PrimaryContactInformationSanitizer::sanitize($primaryContactDetails);
        $isValidate = PrimaryContactInformationValidator::validate($primaryContactDetails);
        if (!$isValidate) {
            return false;
        }
        SessionTool::set('primaryContactDetails', $primaryContactDetails);
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

        $totalPrice = $this->generateTotalPrice();

        if (!$this->saveBooking()) {
            return false;
        }

        //if (!$this->doPayment($totalPrice)) {
        //   return false;
        // }

        return true;
    }

    private function generateTotalPrice()
    {
        $allSessions = SessionTool::getAll();
        $primaryContactDetails = $allSessions['primaryContactDetails'];

        $passengersTotalPrice = $this->calculatePassengersPrice($allSessions);
        $bookServicesTotalPrice = $this->calculateBookServicesPrice($allSessions);
        //Comprobar si caben en el viaje

        error_log("Sesiones" . print_r($allSessions, true));
        error_log("Passengers Total Price: " . print_r($passengersTotalPrice, true));
        error_log("Book services Total Price: " . print_r($bookServicesTotalPrice, true));

        return $passengersTotalPrice + $bookServicesTotalPrice;
    }

    private function calculatePassengersPrice($allSessions)
    {
        require_once './Tools/IniTool.php';

        $flightDetails = $allSessions['flightDetails'];
        $passengersDetails = $allSessions['passengersDetails'];

        $iniTool = new IniTool('./Config/cfg.ini');
        $flightModel = new FlightModel();
        $servicesModel = new ServicesModel();

        $priceSettings = $iniTool->getKeysAndValues("priceSettings");
        $childDiscountPercentage = intval($priceSettings['childDiscountPercentage']);
        $infantDiscountPercentage = intval($priceSettings['infantDiscountPercentage']);

        //Calculate flight price
        $flightPrice = $flightModel->getFlightPrice($flightDetails['flightCode']);

        $discountedPrices = [
            'adult' => $flightPrice,
            'child' => $flightPrice * (1 - $childDiscountPercentage),
            'infant' => $flightPrice * (1 - $infantDiscountPercentage)
        ];
        $passengerCount = ['adult' => 0, 'child' => 0, 'infant' => 0];

        //Calculate passengers price
        $serviceCodes = [];
        foreach ($passengersDetails as $passenger) {
            $passengerCount[$passenger['ageCategory']]++;
            //Recogemos todos los servicios individuales contratados sin repetir de todos los pasajeros
            foreach ($passenger['services'] as $serviceCode) {
                $serviceCodes[$serviceCode] = $serviceCode;
            }
        }

        //Recogemos los precios de la variedad de servicios de nuestros pasajeros
        $servicesWithPrices = $servicesModel->readServicePrices($serviceCodes);
        //Sumamos todos los precios de los servicios individuales
        $passengerServicesPrice = 0.0;
        foreach ($passengersDetails as $passenger) {
            foreach ($passenger['services'] as $serviceCode) {
                $passengerServicesPrice += $servicesWithPrices[$serviceCode];
            }
        }

        $passengerFlightPrice = $passengerCount['adult'] * $discountedPrices['adult'] + $passengerCount['child'] * $discountedPrices['child'] + $passengerCount['infant'] * $discountedPrices['infant'];
        return $passengerFlightPrice + $passengerServicesPrice;
    }

    private function calculateBookServicesPrice($allSessions)
    {
        $bookServicesDetails = $allSessions['bookServicesDetails'];
        $servicesModel = new ServicesModel();
        $servicesWithPrices = $servicesModel->readServicePrices($bookServicesDetails);
        $bookServicesTotalPrice = 0.0;

        foreach ($servicesWithPrices as $price) {
            $bookServicesTotalPrice += $price;
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
}
