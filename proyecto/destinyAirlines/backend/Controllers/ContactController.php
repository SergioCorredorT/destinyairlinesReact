<?php
require_once ROOT_PATH . '/Controllers/BaseController.php';
final class ContactController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        parent::loadFilter('Contact');
    }

    public function sendContact(array $POST): bool
    {
        $contactData = $this->filter->filterSendContactData($POST);
        $contactData = $this->processData->processData($contactData, 'Contact');
        if (!$contactData) {
            return false;
        }

        //Seleccionamos correo destino en función del asunto recibido, que debería estar en cfg.ini, si no, se activa el valor default
        $contactData['toEmail'] = $this->chooseToFromSubject($contactData['subject']);

        //Recogemos del cfg.ini la cuenta remitente de correo
        $cfgOriginEmailIni = $this->iniTool->getKeysAndValues("originEmail");
        $contactData['fromEmail'] = $cfgOriginEmailIni['email'];
        $contactData['fromPassword'] = $cfgOriginEmailIni['password'];

        if (EmailTool::sendEmail($contactData, "contactTemplate")) {
            return true;
        }
        return false;
    }

    private function chooseToFromSubject(string $subject): string
    {
        //devolver el "to" (correo destino) según el subject según el cfg.ini
        $cfgSubjectWithItsEmails = $this->iniTool->getKeysAndValues("destinyContactEmails");
        if (isset($cfgSubjectWithItsEmails[$subject])) {
            return $cfgSubjectWithItsEmails[$subject];
        }

        return $cfgSubjectWithItsEmails["default"];
    }
}
