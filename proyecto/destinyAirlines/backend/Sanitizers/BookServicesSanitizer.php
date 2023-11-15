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

    public static function sanitizeDirection($direction)
    {
        return htmlspecialchars(trim($direction));
    }

    public static function sanitize(array $data)
    {
        $arraySanitized = [];

        if (!empty($data['services'])) $arraySanitized["services"] = self::sanitizeCollectiveServiceCodes($data['services']);
        if (!empty($data['direction'])) $arraySanitized["direction"] = self::sanitizeDirection($data['direction']);

        return $arraySanitized;
    }
}
