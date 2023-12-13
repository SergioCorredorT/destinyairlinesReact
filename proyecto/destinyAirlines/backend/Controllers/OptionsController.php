<?php
require_once './Controllers/BaseController.php';
final class OptionsController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getDocTypes()
    {
        require_once './Tools/IniTool.php';
        $iniTool = new IniTool('./Config/cfg.ini');
        $documentTypes = $iniTool->getKeysAndValues('documentTypes');
        return ['response' => $documentTypes];
    }
    
    public function getAgeCategories()
    {
        
        //adult, child, infant
        return ;
    }
    public function getAssistiveDevices()
    {
        $AdditionalInformationModel = new AdditionalInformationModel();
        return $AdditionalInformationModel->selectAllowedValues('assistiveDevices');
    }

    public function getMedicalEquipments()
    {
        $AdditionalInformationModel = new AdditionalInformationModel();
        return $AdditionalInformationModel->selectAllowedValues('medicalEquipment');
    }

    public function getMobilityLimitations()
    {
        $AdditionalInformationModel = new AdditionalInformationModel();
        return $AdditionalInformationModel->selectAllowedValues('mobilityLimitations');
    }

    public function getCommunicationNeeds()
    {
        $AdditionalInformationModel = new AdditionalInformationModel();
        return $AdditionalInformationModel->selectAllowedValues('communicationNeeds');
    }

    public function getMedicationRequirements()
    {
        $AdditionalInformationModel = new AdditionalInformationModel();
        return $AdditionalInformationModel->selectAllowedValues('medicationRequirements');
    }

    
}
