<?php
require_once "./Models/BaseModel.php";
final class InvoiceModel extends BaseModel
{
    private const table = "INVOICES";

    public function __construct()
    {
        parent::__construct(self::table);
    }

    public function getInvoiceFromIdInvoice(int $idInvoice)
    {
        return parent::select('*', "id_INVOICES = $idInvoice")[0];
    }

    public function updateIsPaid(int $idInvoice)
    {
        return parent::update(['isPaid'=>1], 'id_INVOICES = '.$idInvoice);
    }

    public function createInvoices(array $data, bool $getId = false)
    {
        return parent::insert($data, $getId);
    }

    public function readInvoices()
    {
        return parent::select("*");
    }

    public function updateInvoices(array $data, string $where)
    {
        return parent::update($data, $where);
    }

    public function deleteInvoices(string $where = "")
    {
        return parent::delete($where);
    }
}
