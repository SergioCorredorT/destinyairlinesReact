<?php

class BookServiceModel extends BaseModel
{
    private const table = "BOOKS_SERVICES";

    public function __construct()
    {
        parent::__construct(self::table);
    }
    //estos métodos se modificarán para que hagan únicamente lo necesario
    public function createBookServices($data)
    {
        return parent::insert($data);
    }

    public function readBookServices()
    {
        return parent::select("*");
    }

    public function updateBookServices($data, $where)
    {
        return parent::update($data, $where);
    }

    public function deleteBookServices($where = "")
    {
        return parent::delete($where);
    }
}
