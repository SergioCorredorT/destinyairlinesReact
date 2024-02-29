<?php
require_once ROOT_PATH . '/DataProcessing/BaseProcessData.php';

final class ProcessData extends BaseProcessData
{
    public function __construct()
    {
        parent::__construct();
    }

    public function processData(array $frontendData, string $type): bool | array | string
    {
        $sanitizerClassName = $this->getSanitizerClassName($type);
        $validatorClassName = $this->getValidatorClassName($type);

        if ($sanitizerClassName === null && $validatorClassName === null) {
            return false;
        }

        if ($sanitizerClassName !== null) {
            $data = $sanitizerClassName::sanitize($frontendData);
        } else {
            $data = $frontendData;
        }

        if ($validatorClassName !== null && !$validatorClassName::validate($data)) {
            return false;
        }
        return $data;
    }

    private function getSanitizerClassName(string $type): string | null
    {
        $sanitizerClassName = ucfirst($type) . 'Sanitizer';
        if (class_exists($sanitizerClassName)) {
            return $sanitizerClassName;
        }
        return null;
    }

    private function getValidatorClassName(string $type): string | null
    {
        $validatorClassName = ucfirst($type) . 'Validator';
        if (class_exists($validatorClassName)) {
            return $validatorClassName;
        }
        return null;
    }
}
