<?php
require_once "./Models/BaseModel.php";
final class BookServiceModel extends BaseModel
{
    private const table = "BOOKS_SERVICES";

    public function __construct()
    {
        parent::__construct(self::table);
    }

    public function createMultipleBookServices(array $data, bool $getId = false): bool|string
    {
        return parent::insertMultiple($data, $getId);
    }
}
