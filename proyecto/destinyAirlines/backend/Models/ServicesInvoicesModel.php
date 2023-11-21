<?php
require_once "./Models/BaseModel.php";
final class ServicesInvoicesModel extends BaseModel
{
    private const table = "SERVICES_INVOICES";

    public function __construct()
    {
        parent::__construct(self::table);
    }

    public function createMultipleServicesInvoices(array $data, bool $getId = false)
    {
        return parent::insertMultiple($data, $getId);
    }

    public function createServicesInvoices(array $data, bool $getId = false)
    {
        return parent::insert($data, $getId);
    }

    public function readServicesInvoices()
    {
        return parent::select("*");
    }

    public function updateServicesInvoices(array $data, string $where)
    {
        return parent::update($data, $where);
    }

    public function deleteServicesInvoices(string $where = "")
    {
        return parent::delete($where);
    }
}
