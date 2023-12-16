<?php
require_once "./Models/BaseModel.php";
final class ItineraryModel extends BaseModel
{
    private const table = "ITINERARIES";

    public function __construct()
    {
        parent::__construct(self::table);
    }

    public function readOriginDestiny($idItinerary)
    {
        return parent::select('origin, destiny', "id_ITINERARIES = $idItinerary")[0];
    }

    public function readItineraryFromIdItinerary($idItinerary)
    {
        return parent::select('*', "id_ITINERARIES = $idItinerary")[0];
    }
}
