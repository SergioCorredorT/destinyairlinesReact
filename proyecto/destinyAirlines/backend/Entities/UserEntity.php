<?php

class UserEntity {
    private $id_USERS;
    private $title;
    private $firstName;
    private $lastName;
    private $townCity;
    private $streetAddress;
    private $zipCode;
    private $country;
    private $emailAddress;
    private $password;
    private $phoneNumber1;
    private $phoneNumber2;
    private $phoneNumber3;
    private $companyName;
    private $companyTaxNumber;
    private $companyPhoneNumber;

    public function __construct($params = []) {
        $this->id_USERS = $params['id_USERS'] ?? null;
        $this->title = $params['title'] ?? null;
        $this->firstName = $params['firstName'] ?? null;
        $this->lastName = $params['lastName'] ?? null;
        $this->townCity = $params['townCity'] ?? null;
        $this->streetAddress = $params['streetAddress'] ?? null;
        $this->zipCode = $params['zipCode'] ?? null;
        $this->country = $params['country'] ?? null;
        $this->emailAddress = $params['emailAddress'] ?? null;
        $this->password = $params['password'] ?? null;
        $this->phoneNumber1 = $params['phoneNumber1'] ?? null;
        $this->phoneNumber2 = $params['phoneNumber2'] ?? null;
        $this->phoneNumber3 = $params['phoneNumber3'] ?? null;
        $this->companyName = $params['companyName'] ?? null;
        $this->companyTaxNumber = $params['companyTaxNumber'] ?? null;
        $this->companyPhoneNumber = $params['companyPhoneNumber'] ?? null;
    }

    public function getId_USERS() {
        return $this->id_USERS;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getFirstName() {
        return $this->firstName;
    }

    public function getLastName() {
        return $this->lastName;
    }

    public function getTownCity() {
        return $this->townCity;
    }

    public function getStreetAddress() {
        return $this->streetAddress;
    }

    public function getZipCode() {
        return $this->zipCode;
    }

    public function getCountry() {
        return $this->country;
    }

    public function getEmailAddress() {
        return $this->emailAddress;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getPhoneNumber1() {
        return $this->phoneNumber1;
    }

    public function getPhoneNumber2() {
        return $this->phoneNumber2;
    }

    public function getPhoneNumber3() {
        return $this->phoneNumber3;
    }

    public function getCompanyName() {
        return $this->companyName;
    }

    public function getCompanyTaxNumber() {
        return $this->companyTaxNumber;
    }

    public function getCompanyPhoneNumber() {
        return $this->companyPhoneNumber;
    }

    public function setId_USERS($value) {
        $this->id_USERS = $value;
    }

    public function setTitle($value) {
        $this->title = $value;
    }

    public function setFirstName($value) {
        $this->firstName = $value;
    }

    public function setLastName($value) {
        $this->lastName = $value;
    }

    public function setTownCity($value) {
        $this->townCity = $value;
    }

    public function setStreetAddress($value) {
        $this->streetAddress = $value;
    }

    public function setZipCode($value) {
        $this->zipCode = $value;
    }

    public function setCountry($value) {
        $this->country = $value;
    }

    public function setEmailAddress($value) {
        $this->emailAddress = $value;
    }

    public function setPassword($value) {
        $this->password = $value;
    }

    public function setPhoneNumber1($value) {
        $this->phoneNumber1 = $value;
    }

    public function setPhoneNumber2($value) {
        $this->phoneNumber2 = $value;
    }

    public function setPhoneNumber3($value) {
        $this->phoneNumber3 = $value;
    }

    public function setCompanyName($value) {
        $this->companyName = $value;
    }

    public function setCompanyTaxNumber($value) {
        $this->companyTaxNumber = $value;
    }

    public function setCompanyPhoneNumber($value) {
        $this->companyPhoneNumber = $value;
    }
}
