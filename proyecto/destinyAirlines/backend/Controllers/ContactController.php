<?php
//enviar correo o whatsapp al correo de empresa
require_once './Controllers/BaseController.php';
require_once './Sanitizers/ContactSanitizer.php';
require_once './Validators/ContactValidator.php';
require_once './Tools/IniTool.php';
require_once './Tools/EmailTool.php';

class ContactController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function sendContact($data)
    {
        $contactData=ContactSanitizer::sanitize($data);
        $errors = ContactValidator::validate($contactData);

        if (empty($errors)) {
            $contactData['to'] = $this->chooseToFromSubject($contactData['subject']);
            
            $iniTool = new IniTool('./Config/cfg.ini');
            $originEmailIni = $iniTool->getKeysAndValues("originEmail");
            $contactData['fromEmail'] = $originEmailIni['email'];
            $contactData['fromPassword'] = $originEmailIni['password'];

            if (!EmailTool::sendEmail($contactData)) {
                $errors['sendEmail'] = 1;
            }
        }
        header('Content-Type: application/json');
        echo json_encode(['error' => $errors]);
        exit();
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
