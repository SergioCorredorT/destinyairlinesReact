<?php
require_once "./Models/BaseModel.php";
final class PassengerBookServiceModel extends BaseModel
{
    private const table = "passengers_books_services";

    public function __construct()
    {
        parent::__construct(self::table);
    }

    public function createMultiplePassengerService(array $data, bool $getId = false): bool|string
    {
        return parent::insertMultiple($data, $getId);
    }
}
