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

    public function getEmailById(int $id_USERS): bool|string
    {
        $results = parent::select('emailAddress', "id_USERS = $id_USERS");
        if (empty($results)) {
            return false;
        }
        return $results[0]['emailAddress'];
    }

    public function readUserByEmailPassword(string $email, string $password): bool|array
    {
        $passwordHash = parent::select('passwordHash', "emailAddress = '$email'");

        if (!empty($passwordHash) && password_verify($password, $passwordHash[0]["passwordHash"])) {

            return parent::select('*', "emailAddress = '$email' AND passwordHash = '" . $passwordHash[0]["passwordHash"] . "'");
        }

        return false;
    }

    public function readUserByEmail(string $email): bool|array
    {
        $results = parent::select('*', "emailAddress = '$email' ");
        if($results) {
            return $results[0];
        }
        else {
            return false;
        }
        //return parent::select('*', "emailAddress = '$email' ");
    }

    public function readUserById(int $id_USERS): bool|array
    {
        $results = parent::select('*', "id_USERS = $id_USERS ");
        return $results ? $results[0] : false;
    }

    public function updatePasswordHashById(string $passwordHash, int $id_USERS): bool
    {
        return parent::update(['passwordHash' => "'" . $passwordHash . "'"], " id_USERS = $id_USERS");
    }

    public function updateUsersByEmail(array $data, string $email): bool
    {
        return parent::update($data, " emailAddress = '$email'");
    }

    public function updateAddCurrentLoginAttempts(int $id_USERS): bool
    {
        return parent::update(['currentLoginAttempts' => 'currentLoginAttempts + 1', 'lastAttempt' => "'" . date('Y-m-d H:i:s') . "'"], "id_USERS = $id_USERS");
    }

    public function updateResetCurrentLoginAttempts(int $id_USERS): bool
    {
        return parent::update(['currentLoginAttempts' => 0], " id_USERS = $id_USERS");
    }

    public function updateLastForgotPasswordEmailById(int $id_USERS): bool
    {
        return parent::update(['lastForgotPasswordEmail' => "'" . date('Y-m-d H:i:s') . "'"], " id_USERS = $id_USERS");
    }

    public function deleteUserByEmailAndPassword(string $email, string $password): bool|array
    {
        [$passwordHash] = parent::select('passwordHash', " emailAddress = '$email'");

        if (!empty($passwordHash) && password_verify($password, $passwordHash['passwordHash'])) {
            return parent::delete("emailAddress = '$email'");
        }
        return false;
    }
}
