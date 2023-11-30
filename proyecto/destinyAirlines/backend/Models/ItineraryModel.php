<?php
require_once "./Models/BaseModel.php";
final class ItineraryModel extends BaseModel
{
    private const table = "ITINERARIES";

    public function __construct()
    {
        parent::__construct(self::table);
    }

    public function readItineraryFromIdItinerary($idItinerary)
    {
        return parent::select('*', "id_ITINERARIES = $idItinerary")[0];
    }

    public function createItinerary(array $data, bool $getId = false)
    {
        return parent::insert($data, $getId);
    }

    public function readItinerary()
    {
        return parent::select('*');
    }

    public function updateItinerary(array $data, string $where)
    {
        return parent::update($data, $where);
    }

    public function deleteItinerary(string $where = "")
    {
        return parent::delete($where);
    }
}
