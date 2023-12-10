<?php
require_once './Models/BaseModel.php';
final class PrimaryContactInformationModel extends BaseModel
{
    private const TABLE = "PRIMARY_CONTACT_INFORMATIONS";

    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    public function createPrimaryContactInformation(array $data, bool $getId = false)
    {
        return parent::insert($data, $getId);
    }

    public function readPrimaryContactInformationFromId($idPrimaryContactInfo)
    {
        return parent::select('*', 'id_PRIMARY_CONTACT_INFORMATIONS = '. $idPrimaryContactInfo);
    }

    public function readPrimaryContactInformation()
    {
        return parent::select('*');
    }

    public function updatePrimaryContactInformation(array $data, string $where)
    {
        return parent::update($data, $where);
    }

    public function deletePrimaryContactInformation(string $where = '')
    {
        return parent::delete($where);
    }
}
