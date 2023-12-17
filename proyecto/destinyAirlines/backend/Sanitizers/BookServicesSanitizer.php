<?php
class BookServicesSanitizer
{
    public static function sanitizeCollectiveServiceCodes(array $collectiveServiceCodes): array
    {
        if (is_array($collectiveServiceCodes)) {
            $collectiveServiceCodes = array_map('trim', array_map('htmlspecialchars', $collectiveServiceCodes));
            return array_filter($collectiveServiceCodes);
        }
        return $collectiveServiceCodes;
    }

    public static function sanitize(array $data): array
    {
        if (!empty($data)) $data = self::sanitizeCollectiveServiceCodes($data);

        return $data;
    }
}
