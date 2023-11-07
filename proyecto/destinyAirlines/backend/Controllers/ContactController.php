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

    public function sendContact(array $POST)
    {
        $keys_default = [
            'name' => '',
            'email' => '',
            'phoneNumber' => '',
            'subject' => '',
            'message' => ''
        ];

        foreach ($keys_default as $key => $defaultValue) {
            $contactData[$key] = $POST[$key] ?? $defaultValue;
        }

        $contactData['dateTime'] = date('Y-m-d H:i:s');
        
        $contactData = ContactSanitizer::sanitize($contactData);
        $isValidate = ContactValidator::validate($contactData);
        if ($isValidate) {
            //Seleccionamos correo destino en función del asunto recibido, que debería estar en cfg.ini, si no, se activa el valor default
            $contactData['toEmail'] = $this->chooseToFromSubject($contactData['subject']);

            //Recogemos del cfg.ini la cuenta remitente de correo
            $iniTool = new IniTool('./Config/cfg.ini');
            $cfgOriginEmailIni = $iniTool->getKeysAndValues("originEmail");
            $contactData['fromEmail'] = $cfgOriginEmailIni['email'];
            $contactData['fromPassword'] = $cfgOriginEmailIni['password'];

            if (EmailTool::sendEmail($contactData, "contactTemplate")) {
                return true;
            }
        } else {
            return false;
        }
    }

    private function chooseToFromSubject(string $subject)
    {
        //devolver el "to" (correo destino) según el subject según el cfg.ini
        $iniTool = new IniTool('./Config/cfg.ini');
        $cfgSubjectWithItsEmails = $iniTool->getKeysAndValues("destinyContactEmails");
        if (isset($cfgSubjectWithItsEmails[$subject])) {
            return $cfgSubjectWithItsEmails[$subject];
        }

        return $cfgSubjectWithItsEmails["default"];
    }
}
