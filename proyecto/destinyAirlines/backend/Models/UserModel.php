<?php
require_once "./Models/BaseModel.php";
final class UserModel extends BaseModel
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

    public function readUsers()
    {
        return parent::select("*");
    }

    public function readUserByEmailPassword($email, $password)
    {
        $passwordHash = parent::select("passwordHash", "emailAddress = '$email'");

        if (!empty($passwordHash) && password_verify($password, $passwordHash[0]["passwordHash"])) {
            
            return parent::select("*", "emailAddress = '$email' AND passwordHash = '".$passwordHash[0]["passwordHash"]."'");
        }

        return false;
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
        [$passwordHash] = parent::select("passwordHash", "emailAddress = '$email'");

        if (!empty($passwordHash) && password_verify($password, $passwordHash["passwordHash"])) {
            return parent::delete("emailAddress = '$email'");
        }
        return false;
    }
}
