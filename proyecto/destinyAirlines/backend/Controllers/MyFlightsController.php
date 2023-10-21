<?php
//Ver vuelos hechos, pendientes, editar los servicios hasta cierto punto, crear nuevo vuelo
/*
    //TABLAS QUE SE TOCARÁN EN addFlight()
    Nueva reserva:
    books
    users (quizás registro, sí relación de vuelo)
    books_services (quizás)
    passengers + additional_informations
    passengers_books_services (recuerda que quizás con servicio)
*/


//Checkin
//solo se puede hacer desde 24 a 48 horas antes del vuelo
//se confirma asistencia o se cancela
//se obtiene tarjeta de embarque (que no es lo mismo que la factura)
//si el cliente tiene una reserva, se le notifica por mail y sms (sms se considera servicio con precio)
//se podrá seleccionar asiento si se pagó ese servicio
require_once './Controllers/BaseController.php';
class MyFlightsController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function addFlight()
    {

        $BookModel=new BookModel();
    }

    public function confirmCheckin()
    {

    }

    public function getFlights()
    {

    }

    public function editFlight()
    {

    }
}
