<?php
//enviar correo o whatsapp al correo de empresa
require_once './Controllers/BaseController.php';
require_once './Tools/IniTool.php';
require_once './Tools/EmailTool.php';

class ContactController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function sendContact($contactData)
    {
        //Seleccionamos correo destino en función del asunto recibido, que debería estar en cfg.ini, si no, se activa el valor default
        $contactData['toEmail'] = $this->chooseToFromSubject($contactData['subject']);

        //Recogemos del cfg.ini la cuenta remitente de correo
        $iniTool = new IniTool('./Config/cfg.ini');
        $originEmailIni = $iniTool->getKeysAndValues("originEmail");
        $contactData['fromEmail'] = $originEmailIni['email'];
        $contactData['fromPassword'] = $originEmailIni['password'];

        if (!EmailTool::sendEmail($contactData, "contactTemplate")) {
            throw new Exception("Failed to send email");
        }
    }

    private function chooseToFromSubject($subject)
    {
        //devolver el "to" (correo destino) según el subject según el cfg.ini
        $iniTool = new IniTool('./Config/cfg.ini');
        $subjectWithItsEmails = $iniTool->getKeysAndValues("destinyContactEmails");
        if (isset($subjectWithItsEmails[$subject])) {
            return $subjectWithItsEmails[$subject];
        }

        return $subjectWithItsEmails["default"];
    }
}
