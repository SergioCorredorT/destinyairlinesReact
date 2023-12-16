<?php
require_once "./Models/BaseModel.php";
final class FlightModel extends BaseModel
{
    private const table = "FLIGHTS";

    public function __construct()
    {
        parent::__construct(self::table);
    }

    public function isValidFlight($flightCode)
    {
        return parent::select('flightCode', "freeSeats > 0 AND CONCAT(date, ' ', hour) > NOW() AND flightCode = '$flightCode'");
    }

    public function getFlightDateHourIdItineraryFlightCodeFromIdFlight(int $idFlight)
    { 
        return parent::select('date, hour, id_ITINERARIES, flightCode', "id_FLIGHTS = $idFlight")[0];
    }

    public function getFlightDateHourFromIdFlight(int $idFlight)
    { 
        return parent::select('date, hour', "id_FLIGHTS = $idFlight")[0];
    }

    public function getFlightFromIdFlight(int $idFlight)
    {
        return parent::select('*', "id_FLIGHTS = $idFlight")[0];
    }

    public function getFlightPrice($flightCode)
    {
        return floatval(parent::select('price', 'flightCode = ' . $flightCode)[0]['price']);
    }

    public function getFreeSeats($flightCode)
    {
        return intval(parent::select('freeSeats', 'flightCode = ' . $flightCode)[0]['freeSeats']);
    }

    public function getIdAirplanes($flightCode)
    {
        return intval(parent::select('id_AIRPLANES', "flightCode = '$flightCode' ")[0]['id_AIRPLANES']);
    }

    public function getIdFlightFromFlightCode($flightCode)
    {
        return intval(parent::select('id_FLIGHTS', "flightCode = '$flightCode' ")[0]['id_FLIGHTS']);
    }

    public function decreaseAvailableSeats($seatsNumber, $flightCode) {
        
        return parent::update(['freeSeats' => "freeSeats - $seatsNumber "], "flightCode = '$flightCode' ");
    }
}
