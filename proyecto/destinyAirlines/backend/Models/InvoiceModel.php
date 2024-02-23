<?php
require_once "./Models/BaseModel.php";
final class InvoiceModel extends BaseModel
{
    private const table = "invoices";

    public function __construct()
    {
        parent::__construct(self::table);
    }

    public function getInvoiceFromIdInvoice(int $idInvoice): bool|array
    {
        return parent::select('*', "id_INVOICES = $idInvoice ")[0];
    }

    public function getInvoicesForGetBookInfoFromIdBook(int $idBook): bool|array
    {
        return parent::select('id_INVOICES, invoiceCode, invoicedDate, price, isPaid', "id_BOOKS = $idBook ");
    }

    public function updateIsPaid(int $idInvoice): bool
    {
        return parent::update(['isPaid'=>1], 'id_INVOICES = '.$idInvoice);
    }

    public function createInvoices(array $data, bool $getId = false): bool|array
    {
        return parent::insert($data, $getId);
    }
}
