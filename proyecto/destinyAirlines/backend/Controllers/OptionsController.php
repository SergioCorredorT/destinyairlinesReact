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
        $passengerModel = new PassengerModel();
        return $passengerModel->selectAllowedValues('ageCategory');
    }
    public function getAssistiveDevices()
    {
        $additionalInformationModel = new AdditionalInformationModel();
        return $additionalInformationModel->selectAllowedValues('assistiveDevices');
    }

    public function getMedicalEquipments()
    {
        $additionalInformationModel = new AdditionalInformationModel();
        return $additionalInformationModel->selectAllowedValues('medicalEquipment');
    }

    public function getMobilityLimitations()
    {
        $additionalInformationModel = new AdditionalInformationModel();
        return $additionalInformationModel->selectAllowedValues('mobilityLimitations');
    }

    public function getCommunicationNeeds()
    {
        $additionalInformationModel = new AdditionalInformationModel();
        return $additionalInformationModel->selectAllowedValues('communicationNeeds');
    }

    public function getMedicationRequirements()
    {
        $additionalInformationModel = new AdditionalInformationModel();
        return $additionalInformationModel->selectAllowedValues('medicationRequirements');
    }

    
}
