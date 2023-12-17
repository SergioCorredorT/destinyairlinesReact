<?php
require_once ROOT_PATH . '/Controllers/BaseController.php';
final class OptionsController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getDocTypes(): array
    {
        require_once ROOT_PATH . '/Tools/IniTool.php';
        $iniTool = new IniTool(ROOT_PATH  . '/Config/cfg.ini');
        $documentTypes = $iniTool->getKeysAndValues('documentTypes');
        return ['response' => $documentTypes];
    }

    public function getAgeCategories(): array
    {
        require_once ROOT_PATH . '/Tools/IniTool.php';
        $iniTool = new IniTool(ROOT_PATH  . '/Config/cfg.ini');
        $ageCategories = $iniTool->getKeysAndValues('ageCategories');
        return ['response' => $ageCategories];
    }

    public function getAssistiveDevices(): array|bool
    {
        require_once ROOT_PATH . '/Tools/IniTool.php';
        $iniTool = new IniTool(ROOT_PATH  . '/Config/cfg.ini');
        $additional_informations = $iniTool->getKeysAndValues('additional_informations');
        return $additional_informations['assistiveDevices'];
    }

    public function getMedicalEquipments(): array|bool
    {
        require_once ROOT_PATH . '/Tools/IniTool.php';
        $iniTool = new IniTool(ROOT_PATH  . '/Config/cfg.ini');
        $additional_informations = $iniTool->getKeysAndValues('additional_informations');
        return $additional_informations['medicalEquipment'];
    }

    public function getMobilityLimitations(): array|bool
    {
        require_once ROOT_PATH . '/Tools/IniTool.php';
        $iniTool = new IniTool(ROOT_PATH  . '/Config/cfg.ini');
        $additional_informations = $iniTool->getKeysAndValues('additional_informations');
        return $additional_informations['mobilityLimitations'];
    }

    public function getCommunicationNeeds(): array|bool
    {
        require_once ROOT_PATH . '/Tools/IniTool.php';
        $iniTool = new IniTool(ROOT_PATH  . '/Config/cfg.ini');
        $additional_informations = $iniTool->getKeysAndValues('additional_informations');
        return $additional_informations['communicationNeeds'];
    }

    public function getMedicationRequirements(): array|bool
    {
        require_once ROOT_PATH . '/Tools/IniTool.php';
        $iniTool = new IniTool(ROOT_PATH  . '/Config/cfg.ini');
        $additional_informations = $iniTool->getKeysAndValues('additional_informations');
        return $additional_informations['medicationRequirements'];
    }
}
