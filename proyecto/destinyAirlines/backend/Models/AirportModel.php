<?php
require_once "./Models/BaseModel.php";
final class AirportModel extends BaseModel
{
    private const table = "AIRPORTS";

    public function __construct()
    {
        parent::__construct(self::table);
    }

    public function readAirportFromIdAirport($idAirport)
    {
        return parent::select('*', "id_AIRPORTS = $idAirport")[0];
    }

    public function readAirportNameFromIdAirport($idAirport)
    {
        return parent::select('name', "id_AIRPORTS = $idAirport")[0]['name'];
    }
}
