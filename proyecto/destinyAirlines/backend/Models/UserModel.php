<?php
require_once "./Models/BaseModel.php";
class UserModel extends BaseModel
{
    private const table = "USERS";

    public function __construct()
    {
        parent::__construct(self::table);
    }

    public function createUser($data)
    {
        return parent::insert($data);
    }

    public function createUsers($data)
    {
        return parent::inserts($data);
    }

    public function readUsers()
    {
        return parent::select("*");
    }

    public function updateUsers($data, $where)
    {
        return parent::update($data, $where);
    }

    public function deleteUsers($where)
    {
        return parent::delete($where);
    }
}
