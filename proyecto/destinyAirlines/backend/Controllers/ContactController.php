<?php
//enviar correo o whatsapp al correo de empresa
require_once './Controllers/BaseController.php';
require_once './Tools/IniTool.php';
require_once './Tools/EmailTool.php';
require_once './Sanitizers/ContactSanitizer.php';
require_once './Validators/ContactValidator.php';
final class ContactController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function sendContact($POST)
    {
        $contactData = [
            'name'          => $POST['name'] ?? "",
            'email'         => $POST['email'] ?? "",
            'phoneNumber'   => $POST['phoneNumber'] ?? "",
            'subject'       => $POST['subject'] ?? "",
            'message'       => $POST['message'] ?? "",
            'dateTime'      => date('Y-m-d H:i:s')
        ];

        $contactData = ContactSanitizer::sanitize($contactData);
        $isValidate = ContactValidator::validate($contactData);
        if ($isValidate) {
            //Seleccionamos correo destino en función del asunto recibido, que debería estar en cfg.ini, si no, se activa el valor default
            $contactData['toEmail'] = $this->chooseToFromSubject($contactData['subject']);

            //Recogemos del cfg.ini la cuenta remitente de correo
            $iniTool = new IniTool('./Config/cfg.ini');
            $originEmailIni = $iniTool->getKeysAndValues("originEmail");
            $contactData['fromEmail'] = $originEmailIni['email'];
            $contactData['fromPassword'] = $originEmailIni['password'];

            if (EmailTool::sendEmail($contactData, "contactTemplate")) {
                return true;
            }
        } else {
            return false;
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
