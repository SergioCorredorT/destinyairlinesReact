<?php
require_once "./Models/BaseModel.php";
final class UserModel extends BaseModel
{
    private const table = "users";

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
        if ($results) {
            return $results[0];
        } else {
            return false;
        }
    }

    public function readUserEditableInfoByEmail(string $email): bool|array
    {
        $results = parent::select('title, firstName, lastName, country, townCity, streetAddress, zipCode, phoneNumber1, phoneNumber2, phoneNumber3, companyName, companyTaxNumber, companyPhoneNumber, documentationType, documentCode, expirationDate, dateBirth', "emailAddress = '$email' ");
        if ($results) {
            return $results[0];
        } else {
            return false;
        }
    }

    public function readUserVerifiedByEmail(string $email): bool|array
    {
        $results = parent::select('*', "emailAddress = '$email' AND isEmailVerified = 1");
        if ($results) {
            return $results[0];
        } else {
            return false;
        }
    }

    public function readUserById(int $id_USERS): bool|array
    {
        $results = parent::select('*', "id_USERS = $id_USERS ");
        return $results ? $results[0] : false;
    }

    public function readCurrentLoginAttempts(int $id_USERS): bool|int
    {
        $results = parent::select('currentLoginAttempts', "id_USERS = $id_USERS ");
        return $results ? intval($results[0]) : false;
    }

    public function updateAddCurrentLoginAttempts(int $id_USERS): bool
    {
        return parent::update(['currentLoginAttempts' => 'currentLoginAttempts + 1', 'lastAttempt' => "" . date('Y-m-d H:i:s') . ""], "id_USERS = $id_USERS", ['currentLoginAttempts'=>true]);
    }

    public function updatePasswordHashById(string $passwordHash, int $id_USERS): bool
    {
        return parent::update(['passwordHash' => $passwordHash], " id_USERS = $id_USERS");
    }

    public function updateUsersByEmail(array $data, string $email): bool
    {
        return parent::update($data, " emailAddress = '$email'");
    }

    public function updateIsEmailVerified(int $id_USERS): bool
    {
        return parent::update(["isEmailVerified" => 1], " id_USERS = $id_USERS");
    }


    public function updateResetCurrentLoginAttempts(int $id_USERS): bool
    {
        return parent::update(['currentLoginAttempts' => 0], " id_USERS = $id_USERS");
    }

    public function updateLastForgotPasswordEmailById(int $id_USERS): bool
    {
        return parent::update(['lastForgotPasswordEmail' => date('Y-m-d H:i:s')], " id_USERS = $id_USERS");
    }

    public function deleteUserByEmailAndPassword(string $email, string $password): bool|array
    {
        [$passwordHash] = parent::select('passwordHash', " emailAddress = '$email'");

        if (!empty($passwordHash) && password_verify($password, $passwordHash['passwordHash'])) {
            return parent::delete("emailAddress = '$email'");
        }
        return false;
    }

    public function deleteUserById(int $id_USERS): bool
    {
        return parent::delete("id_USERS = $id_USERS");
    }

    public function deleteUserNoVerifiedById(int $id_USERS): bool
    {
        return parent::delete("id_USERS = $id_USERS AND isEmailVerified = 0");
    }
}
