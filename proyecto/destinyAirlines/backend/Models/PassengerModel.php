<?php
require_once './Models/BaseModel.php';
final class PassengerModel extends BaseModel
{
    private const TABLE = "PASSENGERS";

    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    public function readAllowedValues($columnName)
    {
        return parent::selectAllowedValues($columnName);
    }

    public function isAllowedValue($value, $columnName)
    {
        $allowedValues = parent::selectAllowedValues($columnName);

        if (in_array($value, $allowedValues)) {
            return true;
        }
        return false;
    }

    //------------------------------------------------------------
    public function createPassengers(array $data)
    {
        return parent::insert($data);
    }

    public function readPassengers()
    {
        return parent::select('*');
    }

    public function updatePassengers(array $data, string $where)
    {
        return parent::update($data, $where);
    }

    public function deletePassengers(string $where = '')
    {
        return parent::delete($where);
    }
}
