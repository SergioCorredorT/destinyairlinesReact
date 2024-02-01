<?php
require_once "./Models/BaseModel.php";
final class BookModel extends BaseModel
{
    private const table = "BOOKS";

    public function __construct()
    {
        parent::__construct(self::table);
    }

    public function checkBookCodeWithIdUser($bookCode, $idUser): bool
    {
        $rsp = parent::select('bookCode', "id_USERS = $idUser AND bookCode = '$bookCode' ");
        if (isset($rsp[0]['bookCode'])) {
            return true;
        } else {
            return false;
        }
    }

    public function readFlightIdWithCheckinNull($bookCode, $idUser): bool|string
    {
        $rsp = parent::select('id_FLIGHTS', "id_USERS = $idUser AND bookCode = '$bookCode' AND checkinDate IS NULL");
        if (isset($rsp[0]['id_FLIGHTS'])) {
            return $rsp[0]['id_FLIGHTS'];
        } else {
            return false;
        }
    }

    public function readFlightId($bookCode, $idUser): bool|string
    {
        $rsp = parent::select('id_FLIGHTS', "id_USERS = $idUser AND bookCode = '$bookCode' ");
        if (isset($rsp[0]['id_FLIGHTS'])) {
            return $rsp[0]['id_FLIGHTS'];
        } else {
            return false;
        }
    }

    public function updateChecking(string $bookCode): bool
    {
        return parent::update(['checkinDate' => date('Y-m-d')], "bookCode = '$bookCode'");
    }

    public function deleteBookFromBookCode(string $bookCode): bool
    {
        parent::beginTransaction();
        $where = ' bookCode = ' . $bookCode;
        if(!parent::delete($where)) {
            parent::rollBack();
            return false;
        }
        parent::commit();
        return true;
    }

    public function createBooks(array $data, bool $getId = false): bool|array
    {
        return parent::insert($data, $getId);
    }

    public function readBookFromIdBook(string|int $idBook): bool|array
    {
        return parent::select('*', "id_BOOKS = $idBook ")[0];
    }

    public function readIdBookFromBookCode(string $bookCode): bool|array
    {
        return parent::select('id_BOOKS', "bookCode = '$bookCode'")[0]['id_BOOKS'];
    }
}
