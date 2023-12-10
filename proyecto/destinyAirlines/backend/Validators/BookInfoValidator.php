<?php
class BookInfoValidator
{
    public static function validateBookCode($bookCode)
    {
        if (strlen($bookCode) < 5) {
            return false;
        }
        return true;
    }

    public static function validate($data)
    {
        if (isset($data['bookCode']) && !self::validateBookCode($data['bookCode'])) {
            return false;
        }

        return true;
    }
}
