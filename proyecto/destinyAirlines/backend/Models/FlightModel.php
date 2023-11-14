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

    public function getFlightPrice($flightCode)
    {
        return floatval(parent::select('price', 'flightCode = '.$flightCode)[0]['price']);
    }

//-----------------------------------------------------------------
    public function createBooks(array $data)
    {
        return parent::insert($data);
    }

    public function readBooks()
    {
        return parent::select('*');
    }

    public function updateBooks(array $data, string $where)
    {
        return parent::update($data, $where);
    }

    public function deleteBooks(string $where = "")
    {
        return parent::delete($where);
    }
}
