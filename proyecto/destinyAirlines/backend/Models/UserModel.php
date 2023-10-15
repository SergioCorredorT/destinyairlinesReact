<?php

class UserModel extends BaseModel
{
    private const table = "USERS";

    public function __construct()
    {
        parent::__construct(self::table);
    }
    //estos métodos se modificarán para que hagan únicamente lo necesario
    public function createUsers($data)
    {
        return parent::insert($data);
    }

    public function readUsers()
    {
        return parent::select("*");
    }

    public function updateUsers($data, $where)
    {
        return parent::update($data, $where);
    }

    public function deleteUsers($where = "")
    {
        return parent::delete($where);
    }
}
