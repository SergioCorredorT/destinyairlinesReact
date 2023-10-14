<?php

class BookServiceEntity {
    private $id_BOOKS_SERVICES;
    private $id_BOOKS;
    private $id_SERVICES;

    public function __construct($params = []) {
        $this->id_BOOKS_SERVICES = $params['id_BOOKS_SERVICES'] ?? null;
        $this->id_BOOKS = $params['id_BOOKS'] ?? null;
        $this->id_SERVICES = $params['id_SERVICES'] ?? null;
    }

    public function getId_BOOKS_SERVICES() {
        return $this->id_BOOKS_SERVICES;
    }

    public function getId_BOOKS() {
        return $this->id_BOOKS;
    }

    public function getId_SERVICES() {
        return $this->id_SERVICES;
    }

    public function setId_BOOKS_SERVICES($value) {
        $this->id_BOOKS_SERVICES = $value;
    }

    public function setId_BOOKS($value) {
        $this->id_BOOKS = $value;
    }

    public function setId_SERVICES($value) {
        $this->id_SERVICES = $value;
    }
}
