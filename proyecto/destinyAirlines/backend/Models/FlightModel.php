<?php
require_once "./Models/BaseModel.php";
final class FlightModel extends BaseModel
{
    private const table = "FLIGHTS";

    public function __construct()
    {
        parent::__construct(self::table);
    }

    public function isValidFlight(string $flightCode): bool|array
    {
        return parent::select('flightCode', "freeSeats > 0 AND CONCAT(date, ' ', hour) > NOW() AND flightCode = '$flightCode'");
    }

    public function getFlightDateHourIdItineraryFlightCodeFromIdFlight(int $idFlight): bool|array
    {
        return parent::select('date, hour, id_ITINERARIES, flightCode', "id_FLIGHTS = $idFlight")[0];
    }

    public function getFlightDateHourFromIdFlight(int $idFlight): bool|array
    {
        return parent::select('date, hour', "id_FLIGHTS = $idFlight")[0];
    }

    public function getFlightFromIdFlight(int $idFlight): bool|array
    {
        return parent::select('*', "id_FLIGHTS = $idFlight")[0];
    }

    public function getFlightPrice(string $flightCode): bool|string
    {
        return floatval(parent::select('price', 'flightCode = ' . $flightCode)[0]['price']);
    }

    public function getFreeSeats(string $flightCode): bool|string
    {
        return intval(parent::select('freeSeats', 'flightCode = ' . $flightCode)[0]['freeSeats']);
    }

    public function getIdAirplanes(string $flightCode): bool|string
    {
        return intval(parent::select('id_AIRPLANES', "flightCode = '$flightCode' ")[0]['id_AIRPLANES']);
    }

    public function getIdFlightFromFlightCode(string $flightCode): bool|string
    {
        return intval(parent::select('id_FLIGHTS', "flightCode = '$flightCode' ")[0]['id_FLIGHTS']);
    }

    public function decreaseAvailableSeats(string|int $seatsNumber, string|int $flightCode): bool
    {
        return parent::update(['freeSeats' => "freeSeats - $seatsNumber "], "flightCode = '$flightCode' ", ['freeSeats' => true]);
    }
}
