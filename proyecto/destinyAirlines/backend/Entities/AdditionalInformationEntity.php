<?php

class AdditionalInformationEntity {
    private $id_ADDITIONAL_INFORMATIONS;
    private $id_PASSENGERS;
    private $nationality;
    private $dateBirth;
    private $country;
    private $expirationDate;

    public function __construct($params = []) {
        $this->id_ADDITIONAL_INFORMATIONS = $params['id_ADDITIONAL_INFORMATIONS'] ?? null;
        $this->id_PASSENGERS = $params['id_PASSENGERS'] ?? null;
        $this->nationality = $params['nationality'] ?? null;
        $this->dateBirth = $params['dateBirth'] ?? null;
        $this->country = $params['country'] ?? null;
        $this->expirationDate = $params['expirationDate'] ?? null;
    }

    public function getId_ADDITIONAL_INFORMATIONS() {
        return $this->id_ADDITIONAL_INFORMATIONS;
    }

    public function getId_PASSENGERS() {
        return $this->id_PASSENGERS;
    }

    public function getNationality() {
        return $this->nationality;
    }

    public function getDateBirth() {
        return $this->dateBirth;
    }

    public function getCountry() {
        return $this->country;
    }

    public function getExpirationDate() {
        return $this->expirationDate;
    }

    public function setId_ADDITIONAL_INFORMATIONS($value) {
        $this->id_ADDITIONAL_INFORMATIONS = $value;
    }

    public function setId_PASSENGERS($value) {
        $this->id_PASSENGERS = $value;
    }

    public function setNationality($value) {
        $this->nationality = $value;
    }

    public function setDateBirth($value) {
        $this->dateBirth = $value;
    }

    public function setCountry($value) {
        $this->country = $value;
    }

    public function setExpirationDate($value) {
        $this->expirationDate = $value;
    }
}
