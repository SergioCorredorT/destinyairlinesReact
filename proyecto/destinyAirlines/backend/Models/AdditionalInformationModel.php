<?php
require_once "./Models/BaseModel.php";
final class AdditionalInformationModel extends BaseModel
{
    private const table = "ADDITIONAL_INFORMATIONS";

    public function __construct()
    {
        parent::__construct(self::table);
    }

    public function isAllowedValue($value, $columnName)
    {
        $allowedValues = parent::selectAllowedValues($columnName);

        if (in_array($value, $allowedValues)) {
            return true;
        }
        return false;
    }

//-------------------------------------------------------
    public function createAdditionalInformations(array $data)
    {
        return parent::insert($data);
    }

    public function readAdditionalInformations()
    {
        return parent::select("*");
    }

    public function updateAdditionalInformations(array $data, string $where)
    {
        return parent::update($data, $where);
    }

    public function deleteAdditionalInformations(string $where = "")
    {
        return parent::delete($where);
    }
}
