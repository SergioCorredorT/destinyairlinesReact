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
final class BookController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    //Añade a BBDD la reserva con todo sus datos y se muestra factura pagada para su descarga
    public function addBook()
    {

        $BookModel=new BookModel();
    }

    //Para obtener la tarjeta de embarque, solo se puede hacer desde 24 a 48 hrs antes del vuelo
    public function confirmCheckin()
    {

    }

    public function getBooks()
    {

    }

    public function editFlight()
    {
        
    }
}
