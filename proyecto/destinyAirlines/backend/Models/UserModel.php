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

            return parent::select("*", "emailAddress = '$email' AND passwordHash = '" . $passwordHash[0]["passwordHash"] . "'");
        }

        return false;
    }

    public function readUserByEmail($email)
    {
        return parent::select("*", "emailAddress = '$email' ");
    }

    public function readFailedAttemptsById($id_USERS)
    {
        return parent::select("*", "id_USERS = '$id_USERS' ");
    }

    public function updateUsers($data, $where)
    {
        return parent::update($data, $where);
    }

    public function updatePasswordHashById($passwordHash, $id_USERS)
    {
        return parent::update(" passwordHash = '$passwordHash'", " id_USERS = '$id_USERS'");
    }

    public function updateUsersByEmail($data, $email)
    {
        return parent::update($data, " emailAddress = '$email'");
    }

    public function updateAddFailedAttempts($id_USERS)
    {
        return parent::update(["failedAttempts" => "failedAttempts + 1", "lastFailedAttempt" => "'" .date('Y-m-d H:i:s')."'"], "id_USERS = '$id_USERS'");
    }

    public function updateResetFailedAttempts($id_USERS)
    {
        return parent::update(["failedAttempts" => 0], " id_USERS = '$id_USERS'");
    }

    public function deleteUsers($where)
    {
        return parent::delete($where);
    }

    public function deleteUserByEmailAndPassword($email, $password)
    {
        [$passwordHash] = parent::select("passwordHash", " emailAddress = '$email'");

        if (!empty($passwordHash) && password_verify($password, $passwordHash["passwordHash"])) {
            return parent::delete("emailAddress = '$email'");
        }
        return false;
    }
}
