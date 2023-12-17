<?php
require_once "./Models/BaseModel.php";
final class UserTempIdsModel extends BaseModel
{
    private const table = "USER_TEMP_IDS";

    public function __construct()
    {
        parent::__construct(self::table);
    }

    public function createTempId(int $userId, string $tempId): bool|array
    {
        return parent::insert(["id_USERS" => $userId, "tempID" => $tempId]);
    }

    public function deleteTempIdByUserId(int $userId): bool
    {
        return parent::delete("id_USERS = $userId");
    }

    public function readUserByTempId(string $tempId): bool|array
    {
        return parent::select("*", "tempId = '$tempId' ");
    }

    public function readUserByUserId(int $userId): bool|array
    {
        return parent::select("*", "id_USERS = $userId");
    }

    public function removeTempIdIfExistByIdUser(int $userId): bool
    {
        try {
            return parent::delete("id_USERS = $userId");
        } catch (Exception $e) {
            return false;
        }
    }
}
