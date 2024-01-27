<?php
require_once ROOT_PATH . '/Controllers/BaseController.php';
final class OptionsController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getOptions(array $POST): array
    {
        $rsp = [];
        $options = $POST['listOptions'];
        if (isset($options['docTypesAndRegExp'])) {
            $rsp['docTypesAndRegExp'] = $this->getDocTypes()['response'];
        }
        if (isset($options['docTypesEs'])) {
            $rsp['docTypesEs'] = $this->getDocTypesEs()['response'];
        }
        if (isset($options['titles'])) {
            $rsp['titles'] = $this->getTitles()['response'];
        }
        if (isset($options['countries'])) {
            $rsp['countries'] = $this->getCountryList()['response'];
        }
        return ['response' => $rsp];
    }

    public function getDocTypes(): array
    {
        require_once ROOT_PATH . '/Tools/IniTool.php';
        $iniTool = new IniTool(ROOT_PATH  . '/Config/cfg.ini');
        $documentTypes = $iniTool->getKeysAndValues('documentTypes');
        return ['response' => $documentTypes];
    }

    public function getDocTypesEs(): array
    {
        require_once ROOT_PATH . '/Tools/IniTool.php';
        $iniTool = new IniTool(ROOT_PATH  . '/Config/cfg.ini');
        $documentTypesEs = $iniTool->getKeysAndValues('documentTypesEs');
        return ['response' => $documentTypesEs];
    }

    public function getTitles(): array
    {
        require_once ROOT_PATH . '/Tools/IniTool.php';
        $iniTool = new IniTool(ROOT_PATH  . '/Config/cfg.ini');
        $titles = $iniTool->getKeysAndValues('titles');
        return ['response' => $titles];
    }

    public function getCountryList(): array
    {
        require_once ROOT_PATH . '/Tools/IniTool.php';
        $iniTool = new IniTool(ROOT_PATH  . '/Config/cfg.ini');
        $countryList = $iniTool->getKeysAndValues('countryList');
        return ['response' => $countryList];
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
