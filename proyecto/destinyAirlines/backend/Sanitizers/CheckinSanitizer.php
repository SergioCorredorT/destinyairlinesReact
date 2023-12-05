<?php
class CheckinSanitizer
{
    public static function sanitizeBookCode($bookCode)
    {
        // Elimina todos los caracteres que no sean números, guiones, letras mayúsculas o minúsculas, y letras españolas
        $sanitizedBookCode = preg_replace('/[^0-9a-zA-Z\-áéíóúÁÉÍÓÚñÑ]/u', '', $bookCode);
        return $sanitizedBookCode;
    }
    

    public static function sanitize(array $data)
    {
        //Si es "", o null, o no está definida no se ejecutará el saneamiento
        if (!empty($data['bookCode'])) $data["bookCode"] = self::sanitizeBookCode($data['bookCode']);

        return $data;
    }
}
