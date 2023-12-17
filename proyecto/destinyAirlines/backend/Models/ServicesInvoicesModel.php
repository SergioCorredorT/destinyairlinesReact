<?php
require_once "./Models/BaseModel.php";
final class ServicesInvoicesModel extends BaseModel
{
    private const table = "SERVICES_INVOICES";

    public function __construct()
    {
        parent::__construct(self::table);
    }

    public function readServicesInvoicesFromIdInvoice(int|array $idInvoice): bool|array
    {
        if (is_array($idInvoice)) {
            $idList = implode(',', $idInvoice);
            return parent::select('*', "id_INVOICES IN ($idList)");
        } else {
            return parent::select('*', "id_INVOICES = $idInvoice");
        }
    }

    public function readIdPassengerIdServicesAddRemoveOldPriceFromIdInvoice(int|array $idInvoice): bool|array
    {
        if (is_array($idInvoice)) {
            $idList = implode(',', $idInvoice);
            return parent::select('id_PASSENGERS, id_SERVICES, addRemove, oldPrice', "id_INVOICES IN ($idList)");
        } else {
            return parent::select('id_PASSENGERS, id_SERVICES, addRemove, oldPrice', "id_INVOICES = $idInvoice");
        }
    }

    public function createMultipleServicesInvoices(array $data, bool $getId = false): bool|string
    {
        return parent::insertMultiple($data, $getId);
    }
}
