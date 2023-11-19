<?php
require_once "./Models/BaseModel.php";
final class BookServiceModel extends BaseModel
{
    private const table = "BOOKS_SERVICES";

    public function __construct()
    {
        parent::__construct(self::table);
    }

    public function createBookServices(array $data, bool $getId = false)
    {
        return parent::insert($data, $getId);
    }

    public function readBookServices()
    {
        return parent::select("*");
    }

    public function updateBookServices(array $data, string $where)
    {
        return parent::update($data, $where);
    }

    public function deleteBookServices(string $where = "")
    {
        return parent::delete($where);
    }
}
