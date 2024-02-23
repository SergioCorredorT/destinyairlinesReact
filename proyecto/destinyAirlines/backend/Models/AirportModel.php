<?php
require_once "./Models/BaseModel.php";
final class AirportModel extends BaseModel
{
    private const table = "airports";

    public function __construct()
    {
        parent::__construct(self::table);
    }

    public function readAirportFromIdAirport(string|int $idAirport): bool|array
    {
        return parent::select('*', "id_AIRPORTS = $idAirport")[0];
    }

    public function readAirportNameFromIdAirport(string|int $idAirport): bool|string
    {
        return parent::select('name', "id_AIRPORTS = $idAirport")[0]['name'];
    }
}
