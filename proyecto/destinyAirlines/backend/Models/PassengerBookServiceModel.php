<?php
require_once "./Models/BaseModel.php";
final class PassengerBookServiceModel extends BaseModel
{
    private const table = "PASSENGERS_BOOKS_SERVICES";

    public function __construct()
    {
        parent::__construct(self::table);
    }

    public function createPassengerService(array $data)
    {
        return parent::insert($data);
    }

    public function readPassengerService()
    {
        return parent::select("*");
    }

    public function updatePassengerService(array $data, string $where)
    {
        return parent::update($data, $where);
    }

    public function deletePassengerService(string $where = "")
    {
        return parent::delete($where);
    }
}
