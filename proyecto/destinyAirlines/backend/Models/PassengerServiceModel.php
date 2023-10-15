<?php

class PassengerModel extends BaseModel
{
    private const table = "PASSENGERS_SERVICES";

    public function __construct()
    {
        parent::__construct(self::table);
    }
    //estos métodos se modificarán para que hagan únicamente lo necesario
    public function createPassengerService($data)
    {
        return parent::insert($data);
    }

    public function readPassengerService()
    {
        return parent::select("*");
    }

    public function updatePassengerService($data, $where)
    {
        return parent::update($data, $where);
    }

    public function deletePassengerService($where = "")
    {
        return parent::delete($where);
    }
}
