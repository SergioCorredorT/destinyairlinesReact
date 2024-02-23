<?php
require_once ROOT_PATH . '/Models/BaseModel.php';
final class PrimaryContactInformationModel extends BaseModel
{
    private const TABLE = "primary_contact_informations";

    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    public function createPrimaryContactInformation(array $data, bool $getId = false): bool|array
    {
        return parent::insert($data, $getId);
    }

    public function readPrimaryContactInformationFromId(string|int $idPrimaryContactInfo): bool|array
    {
        return parent::select('*', 'id_PRIMARY_CONTACT_INFORMATIONS = '. $idPrimaryContactInfo);
    }    
}
