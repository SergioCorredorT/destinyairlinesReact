<?php
require_once "./Models/BaseModel.php";
final class PassengerModel extends BaseModel
{
    private const table = "PASSENGERS";

    public function __construct()
    {
        parent::__construct(self::table);
    }

    public function createPassengers(array $data)
    {
        return parent::insert($data);
    }

    public function readPassengers()
    {
        return parent::select("*");
    }

    public function updatePassengers(array $data, string $where)
    {
        return parent::update($data, $where);
    }

    public function deletePassengers(string $where = "")
    {
        return parent::delete($where);
    }
}
