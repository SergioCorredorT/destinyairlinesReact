<?php
class BookServicesSanitizer
{
    public static function sanitizeCollectiveServiceCodes($collectiveServiceCodes)
    {
        if (is_array($collectiveServiceCodes)) {
            return array_map('trim', array_map('htmlspecialchars', $collectiveServiceCodes));
        }
        return $collectiveServiceCodes;
    }

    public static function sanitize(array $data)
    {
        return self::sanitizeCollectiveServiceCodes($data);
    }
}
