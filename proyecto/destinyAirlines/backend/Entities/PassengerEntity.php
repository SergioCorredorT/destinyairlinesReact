<?php

class PassengerEntity {
    private $id_PASSENGERS;
    private $passengerCode;
    private $documentationType;
    private $documentCode;
    private $firstName;
    private $lastName;
    private $title;
    private $ageCategory;
    private $billed;

    public function __construct($params = []) {
        $this->id_PASSENGERS = $params['id_PASSENGERS'] ?? null;
        $this->passengerCode = $params['passengerCode'] ?? null;
        $this->documentationType = $params['documentationType'] ?? null;
        $this->documentCode = $params['documentCode'] ?? null;
        $this->firstName = $params['firstName'] ?? null;
        $this->lastName = $params['lastName'] ?? null;
        $this->title = $params['title'] ?? null;
        $this->ageCategory = $params['ageCategory'] ?? null;
        $this->billed = $params['billed'] ?? null;
    }

    public function getId_PASSENGERS() {
        return $this->id_PASSENGERS;
    }

    public function getPassengerCode() {
        return $this->passengerCode;
    }

    public function getDocumentationType() {
        return $this->documentationType;
    }


    public function getDocumentCode() {
        return $this->documentCode;
    }

    public function getFirstName() {
        return $this->firstName;
    }

    public function getLastName() {
        return $this->lastName;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getAgeCategory() {
        return $this->ageCategory;
    }

    public function getBilled() {
        return $this->billed;
    }

    public function setId_PASSENGERS($value) {
        $this->id_PASSENGERS = $value;
    }

    public function setPassengerCode($value) {
        $this->passengerCode = $value;
    }

    public function setDocumentationType($value) {
        $this->documentationType = $value;
    }

    public function setDocumentCode($value) {
        $this->documentCode = $value;
    }

    public function setFirstName($value) {
        $this->firstName = $value;
    }

    public function setLastName($value) {
        $this->lastName = $value;
    }

    public function setTitle($value) {
        $this->title = $value;
    }

    public function setAgeCategory($value) {
        $this->ageCategory = $value;
    }

    public function setBilled($value) {
        $this->billed = $value;
    }
}
