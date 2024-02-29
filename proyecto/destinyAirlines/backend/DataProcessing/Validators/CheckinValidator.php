<?php
class CheckinValidator
{

    public static function validateBookCode(string $bookCode): bool
    {
        if (strlen($bookCode) < 5) {
            return false;
        }
        return true;
    }

    public static function validate(array $data): bool | array
    {
        if (isset($data['bookCode']) && !self::validateBookCode($data['bookCode'])) {
            return false;
        }

        return $data;
    }
}
