<?php
require_once "./Models/BaseModel.php";
final class BookModel extends BaseModel
{
    private const table = "BOOKS";

    public function __construct()
    {
        parent::__construct(self::table);
    }

    public function checkBookCodeWithIdUser($bookCode, $idUser)
    {
        $rsp = parent::select('bookCode', "id_USERS = $idUser AND bookCode = '$bookCode' ");
        if (isset($rsp[0]['bookCode'])) {
            return true;
        } else {
            return false;
        }
    }

    public function readFlightIdWithCheckinNull($bookCode, $idUser)
    {
        $rsp = parent::select('id_FLIGHTS', "id_USERS = $idUser AND bookCode = '$bookCode' AND checkinDate IS NULL");
        if (isset($rsp[0]['id_FLIGHTS'])) {
            return $rsp[0]['id_FLIGHTS'];
        } else {
            return false;
        }
    }

    public function readFlightId($bookCode, $idUser)
    {
        $rsp = parent::select('id_FLIGHTS', "id_USERS = $idUser AND bookCode = '$bookCode' ");
        if (isset($rsp[0]['id_FLIGHTS'])) {
            return $rsp[0]['id_FLIGHTS'];
        } else {
            return false;
        }
    }

    public function updateChecking(string $bookCode)
    {
        return parent::update(['checkinDate' => '"' . date('Y-m-d') . '"'], "bookCode = '$bookCode'");
    }

    public function deleteBookFromBookCode($bookCode)
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

    public function createBooks(array $data, bool $getId = false)
    {
        return parent::insert($data, $getId);
    }

    public function readBookFromIdBook($idBook)
    {
        return parent::select('*', "id_BOOKS = $idBook")[0];
    }

    public function readIdBookFromBookCode($bookCode)
    {
        return parent::select('id_BOOKS', "bookCode = '$bookCode'")[0]['id_BOOKS'];
    }

    public function readBooks()
    {
        return parent::select("*");
    }

    public function updateBooks(array $data, string $where)
    {
        return parent::update($data, $where);
    }

    public function deleteBooks(string $where = "")
    {
        return parent::delete($where);
    }
}
