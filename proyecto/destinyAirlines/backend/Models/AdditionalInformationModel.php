<?php

class AdditionalInformationModel extends BaseModel
{
    private const table = "ADDITIONAL_INFORMATIONS";

    public function __construct()
    {
        parent::__construct(self::table);
    }
    //estos métodos se modificarán para que hagan únicamente lo necesario
    public function createAdditionalInformations($data)
    {
        return parent::insert($data);
    }

    public function readAdditionalInformations()
    {
        return parent::select("*");
    }

    public function updateAdditionalInformations($data, $where)
    {
        return parent::update($data, $where);
    }

    public function deleteAdditionalInformations($where = "")
    {
        return parent::delete($where);
    }
}
