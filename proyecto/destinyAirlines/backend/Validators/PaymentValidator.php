<?php
class PaymentValidator
{
    public static function validateCardholderName($name)
    {
        if (strlen($name) < 2) {
            return false;
        }
        return true;
    }

    public static function validateCardNumber($number)
    {
        // El número de la tarjeta debe ser numérico y tener una longitud de 16 dígitos
        return is_numeric($number) && strlen((string)$number) == 16;
    }

    public static function validateExpirationDate($expirationDate)
    {
        // Comprueba si la fecha de expiración está en el formato correcto (MM/AA)
        if (!preg_match('/^(0[1-9]|1[0-2])\/[0-9]{2}$/', $expirationDate)) {
            return false;
        }

        // Comprueba si la fecha de expiración es una fecha futura
        $currentDate = date('m/y');
        $exp = strtotime($expirationDate);
        $current = strtotime($currentDate);
        if ($current > $exp) {
            return false;
        }

        return true;
    }

    public static function validateCvc($cvc)
    {
        // El CVC debe ser numérico y tener una longitud de 3 dígitos
        return is_numeric($cvc) && strlen((string)$cvc) == 3;
    }

    public static function validateBillingAddress($address)
    {
        // La dirección de facturación no debe estar vacía y debe ser una cadena
        return !empty($address) && is_string($address);
    }

    public static function validate($data)
    {
        if (isset($data['cardholderName']) && !self::validateCardholderName($data['cardholderName'])) {
            return false;
        }
        if (isset($data['cardNumber']) && !self::validateCardNumber($data['cardNumber'])) {
            return false;
        }
        if (isset($data['expirationDate']) && !self::validateExpirationDate($data['expirationDate'])) {
            return false;
        }
        if (isset($data['cvc']) && !self::validateCvc($data['cvc'])) {
            return false;
        }
        if (isset($data['billingAddress']) && !self::validateBillingAddress($data['billingAddress'])) {
            return false;
        }

        return true;
    }
}
