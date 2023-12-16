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
}
