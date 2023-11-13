<?php
//Ver vuelos/reservas hechos y pendientes, crear reserva, editar los servicios contratados, crear nuevo vuelo
/*
PLAN
    -QUITAR precio de books ya que no tiene sentido, estas filas se crean o añaden
    -AÑADIR tabla invoices con su id, código, fecha y dejar en BOOKS el id foráneo de invoices
*/

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

        $fligthDetails = [
            'flightCode'      => $POST['flightCode'] ?? '',
            'adultsNumber'    => $POST['adultsNumber'] ?? '',
            'childsNumber'    => $POST['childsNumber'] ?? '',
            'infantsNumber'   => $POST['infantsNumber'] ?? '',
            'dateTime'        => date('Y-m-d H:i:s')
        ];

        $fligthDetails = FlightSanitizer::sanitize($fligthDetails);
        $isValidate = FlightValidator::validate($fligthDetails);
        if (!$isValidate) {
            return false;
        }
        SessionTool::set('fligthDetails', $fligthDetails);
        return true;
    }

    public function storePassengerDetails(array $POST)
    {
        require_once './Sanitizers/PassengerSanitizer.php';
        require_once './Validators/PassengerValidator.php';

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
                'SRV001' => null,
                'SRV005' => null,
                'SRV006' => null,
                'SRV007' => null,
                'SRV008' => null,
                'SRV011' => null,
                'SRV013' => null,
                'SRV014' => null,
                'SRV015' => null,
                'SRV016' => null,
                'SRV017' => null,
                'SRV018' => null,
                'SRV019' => null
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

        $accessToken = $POST['accessToken'];
        $accessToken = TokenSanitizer::sanitizeToken($accessToken);
        if (!TokenValidator::validateToken($accessToken)) {
            return false;
        }

        $totalPrice = $this->generateTotalPrice();

        if (!$this->doPayment($totalPrice)) {
            return false;
        }

        return true;
    }

    public function generateTotalPrice()
    {
        //SessionTool::get();
        //Recoger variables de session
        //Comprobar precio en bbdd
        return 11;
    }

    public function doPayment($totalPrice)
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
        if ($this->saveBooking()) {
            $PaymentTool->createPaymentPaypal($paypal['clientId'], $paypal['clientSecret'], $totalPrice, 'EUR', $paypal['returnUrl'], $paypal['cancelUrl']);
            //Aquí solo devolvemos true para que en el frontend ponga un modal con un botón para comprobar si se hizo la compra
            //si sí, avisa de ello, va al index.html y elimina las variables de session del viaje
            //si no, dará opción de volver a intentar compra (simplemente desaparece el modal, y se comprueba si siguen las variables de session y el login, (si no están pues al index y eliminar variables de session)), o de ir al index.html (entonces se eliminan las variables de session del viaje)
            return true;
        }
        return false;
    }

    public function saveBooking()
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
