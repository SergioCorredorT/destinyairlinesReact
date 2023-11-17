<?php
    class Invoice {
/*
	entidad INVOICE, debe contener:
		id del BOOK
		precio total factura o function que lo calcule
		tipo y cód de documento de cada pasajero con sus id de servicios, precios (para oldPrice), addRemove
		ids de servicios, precios (para oldPrice), addRemove
*/

        private $flight;
        private $passengers;
        private $bookServices;

        public function __construct(){

        }

        public function setFlightCode($flightCode) {
            $this->flight['flightCode'] = $flightCode;
        }

        public function setFlightPrice($flightPrice) {
            $this->flight['flightPrice'] = $flightPrice;
        }

        public function addAdultPassenger($adultPassenger) {
            $this->passengers['adults'] = $adultPassenger;
        }

        public function addChildPassenger($childPassenger) {
            $this->passengers['childs'] = $childPassenger;
        }

        public function addInfantPassenger($infantPassenger) {
            $this->passengers['infants'] = $infantPassenger;
        }


    }