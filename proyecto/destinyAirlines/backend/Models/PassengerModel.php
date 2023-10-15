<?php
require_once "./Models/BaseModel.php";
class PassengerModel extends BaseModel
{
    private const table = "PASSENGERS";

    public function __construct()
    {
        parent::__construct(self::table);
    }

    public function createPassengers($data)
    {
        return parent::insert($data);
    }

    public function readPassengers()
    {
        return parent::select("*");
    }

    public function updatePassengers($data, $where)
    {
        return parent::update($data, $where);
    }

    public function deletePassengers($where = "")
    {
        return parent::delete($where);
    }
}
