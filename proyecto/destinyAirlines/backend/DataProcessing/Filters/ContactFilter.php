<?php
require_once ROOT_PATH . '/DataProcessing/Filters/BaseFilter.php';
final class ContactFilter  extends BaseFilter
{
    public function __construct()
    {
        parent::__construct();
    }

    public function filterSendContactData(array $POST): array
    {
        $contactData = null;
        $keys_default = [
            'name' => '',
            'email' => '',
            'phoneNumber' => '',
            'subject' => '',
            'message' => ''
        ];

        foreach ($keys_default as $key => $defaultValue) {
            $contactData[$key] = $POST[$key] ?? $defaultValue;
        }
        return $contactData;
    }
}
