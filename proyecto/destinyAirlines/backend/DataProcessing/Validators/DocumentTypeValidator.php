<?php
class DocumentTypeValidator
{
    public static function validateDocument(array $documentData): bool
    {
        $docType= $documentData['docType'];
        $docCode= $documentData['docCode'];
        if (empty($docType)) {
            return false;
        }

        if (empty($docCode)) {
            return false;
        }

        $docType = strtolower($docType);
        require_once ROOT_PATH . '/Tools/IniTool.php';
        $iniTool = new IniTool(ROOT_PATH  . '/Config/cfg.ini');
        $documentTypes = $iniTool->getKeysAndValues('documentTypes');

        if (!isset($documentTypes[$docType])) {
            return false;
        }

        if (!preg_match($documentTypes[$docType], $docCode)) {
            return false;
        }

        return true;
    }

    public static function validate(array $data): bool | array
    {
        if (isset($data['document']) && !self::validateDocument($data['document'])) {
            return false;
        }
        return $data;
    }
}
