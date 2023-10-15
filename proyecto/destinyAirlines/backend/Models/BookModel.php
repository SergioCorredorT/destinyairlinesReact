<?php
require_once "./Models/BaseModel.php";
class BooksModel extends BaseModel
{
    private const table = "BOOKS";

    public function __construct()
    {
        parent::__construct(self::table);
    }

    public function createBooks($data)
    {
        return parent::insert($data);
    }

    public function readBooks()
    {
        return parent::select("*");
    }

    public function updateBooks($data, $where)
    {
        return parent::update($data, $where);
    }

    public function deleteBooks($where = "")
    {
        return parent::delete($where);
    }
}
