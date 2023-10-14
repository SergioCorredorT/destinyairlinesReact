<?php

class PassengerServiceEntity {
    private $id_PASSENGERS_SERVICES;
    private $id_PASSENGERS;
    private $id_BOOKS;
    private $id_SERVICES;

    public function __construct($params = []) {
        $this->id_PASSENGERS_SERVICES = $params['id_PASSENGERS_SERVICES'] ?? null;
        $this->id_PASSENGERS = $params['id_PASSENGERS'] ?? null;
        $this->id_BOOKS = $params['id_BOOKS'] ?? null;
        $this->id_SERVICES = $params['id_SERVICES'] ?? null;
    }

    public function getId_PASSENGERS_SERVICES() {
        return $this->id_PASSENGERS_SERVICES;
    }

    public function getId_PASSENGERS() {
        return $this->id_PASSENGERS;
    }

    public function getId_BOOKS() {
        return $this->id_BOOKS;
    }

    public function getId_SERVICES() {
        return $this->id_SERVICES;
    }

    public function setId_PASSENGERS_SERVICES($value) {
        $this->id_PASSENGERS_SERVICES = $value;
    }

    public function setId_PASSENGERS($value) {
        $this->id_PASSENGERS = $value;
    }

    public function setId_BOOKS($value) {
        $this->id_BOOKS = $value;
    }

    public function setId_SERVICES($value) {
        $this->id_SERVICES = $value;
    }
}
