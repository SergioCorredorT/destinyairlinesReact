<?php
require_once "./Models/BaseModel.php";
final class UserUnblockInfoModel extends BaseModel
{
    private const table = "USER_UNBLOCK_INFOS";

    public function __construct()
    {
        parent::__construct(self::table);
    }

    public function createUserUnblockInfo($data)
    {
        return parent::insert($data);
    }

    public function readUserUnblockInfos()
    {
        return parent::select("*");
    }

    public function updateUserUnblockInfos($data, $where)
    {
        return parent::update($data, $where);
    }

    public function deleteUserUnblockInfos($where)
    {
        return parent::delete($where);
    }
}
