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

    public function getEmailById($id)
    {
        return parent::select("emailAddress", "id_USERS = '$id'")[0]["emailAddress"];
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

    public function updateUsersByEmail($data, $email)
    {
        return parent::update($data, " WHERE emailAddress = '$email'");
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
