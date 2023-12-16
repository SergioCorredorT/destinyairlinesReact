<?php
require_once ROOT_PATH . '/Models/BaseModel.php';
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

    public function readFirstNameLastNamePassengerCode($idBook)
    {
        return parent::select('firstName, lastName, passengerCode'," id_BOOKS = $idBook ");
    }

    public function createPassengers(array $data, bool $getId = false)
    {
        return parent::insert($data, $getId);
    }
}
