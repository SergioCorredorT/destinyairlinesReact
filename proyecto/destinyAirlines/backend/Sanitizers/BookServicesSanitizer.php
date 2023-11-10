<?php
class BookServicesSanitizer
{
    public static function sanitizeCollectiveServiceCodes($collectiveServiceCodes)
    {
        if (is_array($collectiveServiceCodes)) {
            $collectiveServiceCodes = array_map('trim', array_map('htmlspecialchars', $collectiveServiceCodes));
            return array_filter($collectiveServiceCodes);
        }
        return $collectiveServiceCodes;
    }

    public static function sanitize(array $data)
    {
        return self::sanitizeCollectiveServiceCodes($data);
    }
}
