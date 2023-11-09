<?php
class PaymentSanitizer
{
    public static function sanitizeCardholderName($name)
    {
        return htmlspecialchars(trim($name));
    }

    public static function sanitizeCardNumber($cardNumber)
    {
        return htmlspecialchars(trim($cardNumber));
    }

    public static function sanitizeBillingAddress($billingAddress)
    {
        return htmlspecialchars(trim($billingAddress));
    }

    public static function sanitizeCvc($cvc)
    {
        return htmlspecialchars(trim($cvc));
    }

    public static function sanitizeExpirationDate($expirationDate)
    {
        // Elimina todos los caracteres que no sean números, guiones o barras
        $sanitizedExpirationDate = preg_replace('/[^0-9\-\/]/', '', $expirationDate);
        return $sanitizedExpirationDate;
    }

    public static function sanitize(array $data)
    {
        $arraySanitized = [];
        //Si es "", o null, o no está definida no se ejecutará el saneamiento
        if (!empty($data['cardholderName'])) $arraySanitized["cardholderName"] = self::sanitizeCardholderName($data['cardholderName']);
        if (!empty($data['cardNumber'])) $arraySanitized["cardNumber"] = self::sanitizeCardNumber($data['cardNumber']);
        if (!empty($data['expirationDate'])) $arraySanitized["expirationDate"] = self::sanitizeExpirationDate($data['expirationDate']);
        if (!empty($data['cvc'])) $arraySanitized["cvc"] = self::sanitizeCvc($data['cvc']);
        if (!empty($data['billingAddress'])) $arraySanitized["billingAddress"] = self::sanitizeBillingAddress($data['billingAddress']);

        return $arraySanitized;
    }
}
