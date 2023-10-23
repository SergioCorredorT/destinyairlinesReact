<?php
require_once "./Models/BaseModel.php";
class UserModel extends BaseModel
{
    private const table = "USERS";

    public function __construct()
    {
        parent::__construct(self::table);
    }

    public function createUsers($data)
    {
        return parent::insert($data);
    }

    public function createUser($data)
    {
        return $this->createUsers($data);
    }

    public function readUsers()
    {
        return parent::select("*");
    }

    public function readUserByEmailPassword($email, $password)
    {
        return parent::select("*", "emailAddress = '$email' AND password = '$password'");
    }

    public function updateUsers($data, $where)
    {
        return parent::update($data, $where);
    }

    public function deleteUsers($where)
    {
        return parent::delete($where);
    }

    public function deleteUserByEmailAndPassword($email, $password)
    {
        return parent::delete("emailAddress = '$email' AND password = '$password'");
    }
}
