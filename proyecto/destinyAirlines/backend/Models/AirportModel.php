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

    public function createAirport(array $data, bool $getId = false)
    {
        return parent::insert($data, $getId);
    }

    public function readAirport()
    {
        return parent::select('*');
    }

    public function updateAirport(array $data, string $where)
    {
        return parent::update($data, $where);
    }

    public function deleteAirport(string $where = "")
    {
        return parent::delete($where);
    }
}
