<?php
require_once "./Models/BaseModel.php";
final class UserTempIdsModel extends BaseModel
{
    private const table = "USER_TEMP_IDS";

    public function __construct()
    {
        parent::__construct(self::table);
    }

    public function createTempId(int $userId, string $tempId)
    {
        return parent::insert(["id_USERS" => $userId, "tempID" => $tempId]);
    }

    public function deleteTempIdByUserId(int $userId)
    {
        return parent::delete("id_USERS = $userId");
    }

    public function readUserByTempId(string $tempId)
    {
        return parent::select("*", "tempId = '$tempId' ");
    }

    public function readUserByUserId(int $userId)
    {
        return parent::select("*", "id_USERS = $userId");
    }

    public function removeTempIdIfExistByIdUser(int $userId)
    {
        try {
            return parent::delete("id_USERS = $userId");
        } catch (Exception $e) {
            return false;
        }
    }
    //----------------------------------------------------------

    public function createUserTempIds($data)
    {
        return parent::insert($data);
    }

    public function readUserTempIds()
    {
        return parent::select("*");
    }

    public function updateUserTempIds($data, $where)
    {
        return parent::update($data, $where);
    }

    public function deleteUserTempIds($where = "")
    {
        return parent::delete($where);
    }
}
