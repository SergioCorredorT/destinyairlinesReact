<?php
//Ver vuelos/reservas hechos y pendientes, crear reserva, editar los servicios contratados, crear nuevo vuelo
/*
PLAN
    -AÑADIR adultsNumber childsNumber infantsNumber a books como tinyint
    -QUITAR precio de books ya que no tiene sentido, estas filas se crean o añaden
    -AÑADIR tabla PrimaryContactInformation
    -AÑADIR tabla invoices con su id, código, fecha y dejar en BOOKS el id foráneo de invoices
    -ACTUALIZAR UML y revisar plan

    BookController
        fase book
            bookEntity
                un id vuelo
                un id user
                bookcode se autogenera
                números de pasajeros
                dirección
        fase passengers
            passengerEntity (contiene info adicional también)
                cada pasajero es una posición asociativa del array (adults, infants,...) que se meterá en session
                cada entidad tiene un arraylist de serviceEntity individuales
                un bebé es otra entidad más pero con otros datos que normalmente estarían a null en la entidad
        fase services
            serviceEntity
                cada servicio es una posición de un array que se mete en session
        fase PrimaryContactInformation
            fase PrimaryContactInformationEntity
                esto se guarda en session (tras el payment se insertará en la tabla PrimaryContactInformation)
        fase payment
            paymentEntity
            se hace el pago
            se inserta todo en bbdd
            se genera factura
*/

//Checkin
//solo se puede hacer desde 24 a 48 horas antes del vuelo
//se confirma asistencia o se cancela
//se obtiene tarjeta de embarque (que no es lo mismo que la factura)
//si el cliente tiene una reserva, se le notifica por mail y sms (sms se considera servicio con precio)
//se podrá seleccionar asiento si se pagó ese servicio
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
        if ($isValidate) {
            //Metemos en session
            SessionTool::set('fligthDetails', $fligthDetails);
        }
    }

    public function storePassengerDetails(array $POST)
    {
        require_once './Sanitizers/PassengersSanitizer.php';
        require_once './Validators/PassengersValidator.php';

        $passengers = $POST['passengers'];
        $passegersDetails = [];

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
                'individualServiceCodes' => null //Este campo debe ser un array
            ];

            foreach ($keys_default as $key => $defaultValue) {
                $passengerDetails[$key] = $passenger[$key] ?? $defaultValue;
            }

            //Cada pasajero lo saneamos y validamos
            $passengerDetails = PassengerSanitizer::sanitize($passengerDetails);
            $isValidate = PassengerValidator::validate($passengerDetails);
            if(!$isValidate) {
                return false;
            }

            array_push($passegersDetails, $passengerDetails);
        }

        //Si todo ha ido bien metemos en session a los pasajeros
        SessionTool::set('passengersDetails', $passegersDetails);
    }

    public function storeBookServicesDetails(array $POST)
    {
        //$POST será un array de códigos de servicios
        require_once './Sanitizers/BookServicesSanitizer.php';
        require_once './Validators/BookServicesValidator.php';


        $servicesDetails = $POST;

        //Sanear el array de servicesDetails
        //Validar si todos están en bbdd
        $servicesDetails = BookServicesSanitizer::sanitize($servicesDetails);
        $isValidate      = BookServicesValidator::validate($servicesDetails);

        if ($isValidate) {
            //Metemos en session
            SessionTool::set('bookServicesDetails', $servicesDetails);
        }
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
            'companyPhoneNumber' => null
        ];

        foreach ($primaryContactDetails as $key => $defaultValue) {
            $primaryContactDetails[$key] = $POST[$key] ?? $defaultValue;
        }

        $primaryContactDetails = PrimaryContactDetailsSanitizer::sanitize($primaryContactDetails);
        $isValidate = PrimaryContactDetailsValidator::validate($primaryContactDetails);
        if($isValidate) {
            SessionTool::set('primaryContactDetails', $primaryContactDetails);
        }
    }
    public function doPayment(array $POST)
    {

        $this->saveBook();
    }

    //Añade a BBDD la reserva con todo sus datos y se muestra factura pagada para su descarga
    public function saveBook()
    {
        $BookModel = new BookModel();
        //Enviar factura al email o/ mostrarla en un modal
    }

    //Para obtener la tarjeta de embarque, solo se puede hacer desde 24 a 48 hrs antes del vuelo
    public function confirmCheckin(array $POST)
    {
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
