<?php
require_once ROOT_PATH . '/Models/BaseModel.php';
final class PassengerModel extends BaseModel
{
    private const TABLE = "passengers";

    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    public function readAllowedValues(string $columnName): bool|array
    {
        return parent::selectAllowedValues($columnName);
    }

    public function isAllowedValue(string|int $value, string $columnName): bool
    {
        $allowedValues = parent::selectAllowedValues($columnName);

        if (in_array($value, $allowedValues)) {
            return true;
        }
        return false;
    }

    public function readFirstNameLastNamePassengerCode(string|int $idBook): bool|array
    {
        return parent::select('firstName, lastName, passengerCode'," id_BOOKS = $idBook ");
    }

    public function createPassengers(array $data, bool $getId = false): bool|array
    {
        return parent::insert($data, $getId);
    }
}
