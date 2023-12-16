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

    public function createMultipleAdditionalInformations(array $data)
    {
        return parent::insertMultiple($data);
    }
}
