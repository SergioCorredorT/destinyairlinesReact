<?php

class BooksEntity {
    private $id_BOOKS;
    private $id_FLIGHTS;
    private $id_USERS;
    private $bookCode;
    private $price;
    private $direction;
    private $invoiced;
    private $checkinDate;

    public function __construct($params = []) {
        $this->id_BOOKS = $params['id_BOOKS'] ?? null;
        $this->id_FLIGHTS = $params['id_FLIGHTS'] ?? null;
        $this->id_USERS = $params['id_USERS'] ?? null;
        $this->bookCode = $params['bookCode'] ?? null;
        $this->checkinDate = $params['checkinDate'] ?? null;
        $this->price = $params['price'] ?? null;
        $this->direction = $params['direction'] ?? null;
        $this->invoiced = $params['invoiced'] ?? null;
    }

    public function getId_BOOKS() {
        return $this->id_BOOKS;
    }

    public function getId_FLIGHTS() {
        return $this->id_FLIGHTS;
    }

    public function getId_USERS() {
        return $this->id_USERS;
    }

    public function getBookCode() {
        return $this->bookCode;
    }

    public function getPrice() {
        return $this->price;
    }

    public function getDirection() {
        return $this->direction;
    }

    public function getInvoiced() {
        return $this->invoiced;
    }

    public function getCheckinDate() {
        return $this->checkinDate;
    }

    public function setId_BOOKS($value) {
        $this->id_BOOKS = $value;
    }

    public function setId_FLIGHTS($value) {
        $this->id_FLIGHTS = $value;
    }

    public function setId_USERS($value) {
        $this->id_USERS = $value;
    }

    public function setBookCode($value) {
        $this->bookCode = $value;
    }

    public function setPrice($value) {
        $this->price = $value;
    }

    public function setDirection($value) {
        $this->direction = $value;
    }

    public function setInvoiced($value) {
        $this->invoiced = $value;
    }

    public function setCheckinDate($value) {
        $this->checkinDate = $value;
    }
}
