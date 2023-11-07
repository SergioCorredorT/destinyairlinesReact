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
            $passegerDetails = [];

            $keys_default = [
                'documentationType' => '',
                'documentCode' => '',
                'expirationDate' => '',
                'title' => '',
                'firstName' => '',
                'lastName' => '',
                'ageCategory' => '',
                'nationality' => '',
                'country' => '',
                'dateBirth' => '',
                'assistiveDevices' => '',
                'medicalEquipment' => '',
                'mobilityLimitations' => '',
                'communicationNeeds' => '',
                'medicationRequirements' => ''
            ];

            foreach ($keys_default as $key => $defaultValue) {
                $passegerDetails[$key] = $passenger[$key] ?? $defaultValue;
            }

            //Cada pasajero lo saneamos y validamos
            $passengersDetails = PassengerSanitizer::sanitize($passegerDetails);
            $isValidate = PassengerValidator::validate($passengersDetails);
            if(!$isValidate) {
                return false;
            }

            array_push($passegersDetails, $passegerDetails);
        }

        //Si todo ha ido bien metemos en session a los pasajeros
        SessionTool::set('passengersDetails', $passegersDetails);
    }

    public function storeServicesDetails(array $POST)
    {
        
    }

    public function storePrimaryContactInformationDetails(array $POST)
    {
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
