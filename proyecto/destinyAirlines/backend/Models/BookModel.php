<?php
require_once "./Models/BaseModel.php";
final class BookModel extends BaseModel
{
    private const table = "BOOKS";

    public function __construct()
    {
        parent::__construct(self::table);
    }

    public function createBooks(array $data, bool $getId = false)
    {
        return parent::insert($data, $getId);
    }

    public function readBooks()
    {
        return parent::select("*");
    }

    public function updateBooks(array $data, string $where)
    {
        return parent::update($data, $where);
    }

    public function deleteBooks(string $where = "")
    {
        return parent::delete($where);
    }
}
