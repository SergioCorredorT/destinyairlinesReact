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

    public function sendContact()
    {
        $contactData = [
            'name'      => isset($_POST['name'])            ? ContactSanitizer::sanitizeName($_POST['name'])                : null,
            'email'     => isset($_POST['email'])           ? ContactSanitizer::sanitizeEmail($_POST['email'])              : null,
            'phone'     => isset($_POST['phoneNumber'])     ? ContactSanitizer::sanitizePhoneNumber($_POST['phoneNumber'])  : null,
            'subject'   => isset($_POST['subject'])         ? ContactSanitizer::sanitizeSubject($_POST['subject'])          : null,
            'message'   => isset($_POST['message'])         ? ContactSanitizer::sanitizeMessage($_POST['message'])          : null,
            'dateTime'  => date('Y-m-d H:i:s')
        ];
/*
//PARA PRUEBAS
      $contactData = [
            'name'      => ContactSanitizer::sanitizeName("Sergio"),
            'email'     => ContactSanitizer::sanitizeName("waa@gmail.com"),
            'phone'     => ContactSanitizer::sanitizeName("111223344"),
            'subject'   => ContactSanitizer::sanitizeName("motivazo bueno"),
            'message'   => ContactSanitizer::sanitizeName("Mensaje guauuuuuuuuuuuuuuu"),
            'dateTime'  => date('Y-m-d H:i:s')
        ];
*/
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
$a=new ContactController();
$a->sendContact();