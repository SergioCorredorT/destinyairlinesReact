<?php
require_once "./Models/BaseModel.php";
final class AirplaneModel extends BaseModel
{
    private const table = "airplanes";

    public function __construct()
    {
        parent::__construct(self::table);
    }

    public function getSeats(string|int $idAirplane): bool|array
    {
        return parent::select('seats', " id_AIRPLANES = $idAirplane ");
    }
}
