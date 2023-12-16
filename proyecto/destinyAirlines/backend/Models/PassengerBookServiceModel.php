<?php
require_once "./Models/BaseModel.php";
final class PassengerBookServiceModel extends BaseModel
{
    private const table = "PASSENGERS_BOOKS_SERVICES";

    public function __construct()
    {
        parent::__construct(self::table);
    }

    public function createMultiplePassengerService(array $data, bool $getId = false)
    {
        return parent::insertMultiple($data, $getId);
    }
}
