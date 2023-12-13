<?php
class DocumentTypeValidator
{
    public static function validateDocumentType(string $docType, string $docCode)
    {
        if (empty($docType)) {
            return false;
        }

        if (empty($docCode)) {
            return false;
        }

        $docType = strtolower($docType);
        require_once './Tools/IniTool.php';
        $iniTool = new IniTool('./Config/cfg.ini');
        $documentTypes = $iniTool->getKeysAndValues('documentTypes');
        if (!isset($documentTypes[$docType])) {
            return false;
        }

        if (!preg_match($documentTypes[$docType], $docCode)) {
            return false;
        }

        return true;
    }
}
