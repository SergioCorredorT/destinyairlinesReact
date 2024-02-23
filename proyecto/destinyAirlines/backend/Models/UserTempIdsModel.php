<?php
require_once "./Models/BaseModel.php";
final class UserTempIdsModel extends BaseModel
{
    private const table = "user_temp_ids";

    public function __construct()
    {
        parent::__construct(self::table);
    }

    public function createTempId(int $userId, string $tempId, string $recordCause): bool|array
    {
        return parent::insert(["id_USERS" => $userId, "tempID" => $tempId, "recordCause" => $recordCause]);
    }

    public function deleteTempIdByUserId(int $userId, string $recordCause): bool
    {
        return parent::delete("id_USERS = $userId AND recordCause = '$recordCause'");
    }

    public function readUserByTempId(string $tempId, string $recordCause): bool|array
    {
        return parent::select("*", "tempId = '$tempId' AND recordCause = '$recordCause'");
    }

    public function readUserByUserId(int $userId, string $recordCause): bool|array
    {
        return parent::select("*", "id_USERS = $userId AND recordCause = '$recordCause'");
    }

    public function removeTempIdIfExistByIdUser(int $userId, string $recordCause): bool
    {
        try {
            return parent::delete("id_USERS = $userId AND recordCause = '$recordCause'");
        } catch (Exception $e) {
            return false;
        }
    }
}
