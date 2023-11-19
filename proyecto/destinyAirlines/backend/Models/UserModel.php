<?php
require_once "./Models/BaseModel.php";
final class UserModel extends BaseModel
{
    private const table = "USERS";

    public function __construct()
    {
        parent::__construct(self::table);
    }

    public function createUser(array $data, bool $getId = false)
    {
        return parent::insert($data, $getId);
    }

    public function readUsers()
    {
        return parent::select('*');
    }

    public function getEmailById(int $id_USERS)
    {
        return parent::select('emailAddress', "id_USERS = $id_USERS")[0]['emailAddress'];
    }

    public function readUserByEmailPassword(string $email, string $password)
    {
        $passwordHash = parent::select('passwordHash', "emailAddress = '$email'");

        if (!empty($passwordHash) && password_verify($password, $passwordHash[0]["passwordHash"])) {

            return parent::select('*', "emailAddress = '$email' AND passwordHash = '" . $passwordHash[0]["passwordHash"] . "'");
        }

        return false;
    }

    public function readUserByEmail(string $email)
    {
        return parent::select('*', "emailAddress = '$email' ");
    }

    public function readUserById(int $id_USERS)
    {
        return parent::select('*', "id_USERS = $id_USERS ");
    }

    public function updateUsers(array $data, string $where)
    {
        return parent::update($data, $where);
    }

    public function updatePasswordHashById(string $passwordHash, int $id_USERS)
    {
        return parent::update(['passwordHash' => "'" . $passwordHash . "'"], " id_USERS = $id_USERS");
    }

    public function updateUsersByEmail(array $data, string $email)
    {
        return parent::update($data, " emailAddress = '$email'");
    }

    public function updateAddCurrentLoginAttempts(int $id_USERS)
    {
        return parent::update(['currentLoginAttempts' => 'currentLoginAttempts + 1', 'lastAttempt' => "'" . date('Y-m-d H:i:s') . "'"], "id_USERS = $id_USERS");
    }

    public function updateResetCurrentLoginAttempts(int $id_USERS)
    {
        return parent::update(['currentLoginAttempts' => 0], " id_USERS = $id_USERS");
    }

    public function updateLastForgotPasswordEmailById(int $id_USERS)
    {
        return parent::update(['lastForgotPasswordEmail' => "'" . date('Y-m-d H:i:s') . "'"], " id_USERS = $id_USERS");
    }

    public function deleteUsers(string $where)
    {
        return parent::delete($where);
    }

    public function deleteUserByEmailAndPassword(string $email, string $password)
    {
        [$passwordHash] = parent::select('passwordHash', " emailAddress = '$email'");

        if (!empty($passwordHash) && password_verify($password, $passwordHash['passwordHash'])) {
            return parent::delete("emailAddress = '$email'");
        }
        return false;
    }
}
