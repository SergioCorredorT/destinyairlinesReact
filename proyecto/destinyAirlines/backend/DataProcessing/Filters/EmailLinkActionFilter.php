<?php
require_once ROOT_PATH . '/DataProcessing/Filters/BaseFilter.php';
final class EmailLinkActionFilter  extends BaseFilter
{
    public function __construct()
    {
        parent::__construct();
    }

    public function filterGoToPasswordResetData(array $GET): array
    {
        $goToPasswordResetData = null;
        $keys_default = [
            'type' => '',
            'passwordResetToken' => '',
            'tempId' => null
        ];
        foreach ($keys_default as $key => $defaultValue) {
            $goToPasswordResetData[$key] = $GET[$key] ?? $defaultValue;
        }
        return $goToPasswordResetData;
    }

    public function filterGoToEmailVerificationData(array $GET): array
    {
        $goToEmailVerificationData = null;
        $keys_default = [
            'emailVerificationToken' => '',
            'tempId' => '',
            'type' => ''
        ];

        foreach ($keys_default as $key => $defaultValue) {
            $goToEmailVerificationData[$key] = $GET[$key] ?? $defaultValue;
        }
        return $goToEmailVerificationData;
    }

    public function filterGoToAccountDeletionData(array $GET): array
    {
        $goToAccountDeletionData = null;
        $keys_default = [
            'accountDeletionToken' => '',
            'tempId' => '',
            'type' => ''
        ];

        foreach ($keys_default as $key => $defaultValue) {
            $goToAccountDeletionData[$key] = $GET[$key] ?? $defaultValue;
        }
        return $goToAccountDeletionData;
    }
}
