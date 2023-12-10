<?php
require_once './Database/Database.php';
require_once './Tools/IniTool.php';
require_once './Models/BaseMultiModel.php';
final class MultiModel extends BaseMultiModel
{
    public function __construct()
    {
        parent::__construct();
        spl_autoload_register(function (string $class_name) {

            $file = './models/' . $class_name . '.php';

            if (file_exists($file)) {

                require_once $file;
            }
        });
    }

    public function getSummaryBooks($idUser)
    {
        $sql =
        'SELECT b.bookCode, b.checkinDate, f.date, a.name AS destiny
        FROM books b
        JOIN flights f ON b.id_FLIGHTS = f.id_FLIGHTS
        JOIN itineraries i ON f.id_ITINERARIES = i.id_ITINERARIES
        JOIN airports a ON i.destiny = a.id_AIRPORTS
        WHERE b.id_USERS = :idUser';

        /*
            cod. de reserva.
            Estado checkin.
            Fecha vuelo.
            nombre aeropuerto destino.
        */
        return parent::executeSql($sql, ['idUser' => $idUser]);
    }

    public function getBookInfo($bookCode, $idUser)
    {
        require_once './Models/BookModel.php';
        $bookModel = new BookModel();
        if (!$bookModel->checkBookCodeWithIdUser($bookCode, $idUser)) {
            return false;
        }

        $bookInfo = [];

        $sqlMultiTable =
            'SELECT b.id_BOOKS, b.bookCode, b.direction, b.checkinDate, f.date, f.hour, f.arrivalHour, a1.name AS origin, a2.name AS destiny, id_PRIMARY_CONTACT_INFORMATIONS
        FROM books b
        JOIN flights f ON b.id_FLIGHTS = f.id_FLIGHTS
        JOIN itineraries i ON f.id_ITINERARIES = i.id_ITINERARIES
        JOIN airports a1 ON i.origin = a1.id_AIRPORTS
        JOIN airports a2 ON i.destiny = a2.id_AIRPORTS
        WHERE b.bookCode = :bookCode';
        $book_flight_results = parent::executeSql($sqlMultiTable, ['bookCode' => $bookCode]);
        if (!$book_flight_results) {
            return false;
        }
        $bookInfo = [
            'book' => [
                'bookCode' => $book_flight_results[0]['bookCode'],
                'direction' => $book_flight_results[0]['direction'],
                'checkinDate' => $book_flight_results[0]['checkinDate']
            ],
            'flight' => [
                'date' => $book_flight_results[0]['date'],
                'hour' => $book_flight_results[0]['hour'],
                'arrivalHour' => $book_flight_results[0]['arrivalHour'],
                'origin' => $book_flight_results[0]['origin'],
                'destiny' => $book_flight_results[0]['destiny']
            ]
        ];

        $idPrimaryContactInfo = $book_flight_results[0]['id_PRIMARY_CONTACT_INFORMATIONS'];
        $primaryContactInfo = new PrimaryContactInformationModel();
        $primaryContactInfoResults = $primaryContactInfo->readPrimaryContactInformationFromId($idPrimaryContactInfo);
        if (!$primaryContactInfoResults) {
            return false;
        }
        $bookInfo['primaryContactInfo'] = [
            'documentationType' => $primaryContactInfoResults[0]['documentationType'],
            'documentCode' => $primaryContactInfoResults[0]['documentCode'],
            'expirationDate' => $primaryContactInfoResults[0]['expirationDate'],
            'firstName' => $primaryContactInfoResults[0]['firstName'],
            'lastName' => $primaryContactInfoResults[0]['lastName'],
            'emailAddress' => $primaryContactInfoResults[0]['emailAddress'],
            'phoneNumber1' => $primaryContactInfoResults[0]['phoneNumber1'],
            'phoneNumber2' => $primaryContactInfoResults[0]['phoneNumber2'],
            'country' => $primaryContactInfoResults[0]['country'],
            'townCity' => $primaryContactInfoResults[0]['townCity'],
            'streetAddress' => $primaryContactInfoResults[0]['streetAddress'],
            'zipCode' => $primaryContactInfoResults[0]['zipCode'],
            'companyName' => $primaryContactInfoResults[0]['companyName'],
            'companyTaxNumber' => $primaryContactInfoResults[0]['companyTaxNumber'],
            'companyPhoneNumber' => $primaryContactInfoResults[0]['companyPhoneNumber']
        ];

        $invoiceModel = new InvoiceModel();
        $invoiceResults = $invoiceModel->getInvoicesForGetBookInfoFromIdBook($book_flight_results[0]['id_BOOKS']);
        $bookInfo['invoice'] = $invoiceResults;

        
        /*
            Información de la reserva: Esto incluye el número de reserva, la fecha de la reserva y el estado de la reserva (por ejemplo, confirmada, pendiente, cancelada).

            Detalles del vuelo: Esto incluye el número de vuelo, la aerolínea, las fechas y horas de salida y llegada, los aeropuertos de salida y llegada, y cualquier detalle de las escalas si las hay.

            Información de pasajeros: Esto incluye el nombre del pasajero, el número de asiento y la clase de servicio (por ejemplo, económica, ejecutiva, primera clase).

            Detalles del pago: Esto incluye el precio del billete, los impuestos y tasas, el método de pago utilizado y el estado del pago.
        */
        return $bookInfo;
    }
}
