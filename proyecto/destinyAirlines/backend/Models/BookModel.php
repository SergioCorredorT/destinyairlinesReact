<?php
require_once "./Models/BaseModel.php";
final class BookModel extends BaseModel
{
    private const table = "BOOKS";

    public function __construct()
    {
        parent::__construct(self::table);
    }

    public function readFlightId($bookCode, $idUser)
    {
        $rsp = parent::select('id_FLIGHTS', "id_USERS = $idUser AND bookCode = '$bookCode' AND checkinDate IS NULL");
        if (isset($rsp[0]['id_FLIGHTS'])) {
            return $rsp[0]['id_FLIGHTS'];
        } else {
            return false;
        }
    }

    public function updateChecking(string $bookCode)
    {
        return parent::update(['checkinDate' => '"'.date('Y-m-d').'"'], "bookCode = '$bookCode'");
    }

    public function createBooks(array $data, bool $getId = false)
    {
        return parent::insert($data, $getId);
    }

    public function readBookFromIdBook($idBook)
    {
        return parent::select('*', "id_BOOKS = '$idBook'")[0];
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
