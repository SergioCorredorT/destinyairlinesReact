<?php
require_once "./Models/BaseModel.php";
final class UserTempIdsModel extends BaseModel
{
    private const table = "USER_TEMP_IDS";

    public function __construct()
    {
        parent::__construct(self::table);
    }

    public function createTempId($userId, $tempId)
    {
        return parent::insert(["id_USERS" => $userId, "tempID" => $tempId]);
    }

    public function deleteTempIdByUserId($userId)
    {
        return parent::delete("id_USERS = $userId");
    }

    public function readUserByTempId($tempId)
    {
        return parent::select("*", "tempId = '$tempId' ");
    }

    public function readUserByUserId($userId)
    {
        return parent::select("*", "id_USERS = $userId");
    }

    public function removeTempIdIfExistByIdUser($userId)
    {
        try {
            return parent::delete("id_USERS = $userId");
        } catch (Exception $e) {
            return false;
        }
    }
    //----------------------------------------------------------

    public function createUserTempIdsModel($data)
    {
        return parent::insert($data);
    }

    public function readUserTempIdsModel()
    {
        return parent::select("*");
    }

    public function updateUserTempIdsModel($data, $where)
    {
        return parent::update($data, $where);
    }

    public function deleteUserTempIdsModel($where = "")
    {
        return parent::delete($where);
    }
}
