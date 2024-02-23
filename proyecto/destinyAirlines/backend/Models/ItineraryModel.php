<?php
require_once "./Models/BaseModel.php";
final class ItineraryModel extends BaseModel
{
    private const table = "itineraries";

    public function __construct()
    {
        parent::__construct(self::table);
    }

    public function readOriginDestiny(string|int $idItinerary): bool|array
    {
        return parent::select('origin, destiny', "id_ITINERARIES = $idItinerary")[0];
    }

    public function readItineraryFromIdItinerary(string|int $idItinerary): bool|array
    {
        return parent::select('*', "id_ITINERARIES = $idItinerary")[0];
    }
}
