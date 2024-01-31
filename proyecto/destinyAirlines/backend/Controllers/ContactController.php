<?php
require_once ROOT_PATH . '/Controllers/BaseController.php';
require_once ROOT_PATH . '/Sanitizers/ContactSanitizer.php';
require_once ROOT_PATH . '/Validators/ContactValidator.php';
final class ContactController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function sendContact(array $POST): bool
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

        $contactData = ContactSanitizer::sanitize($contactData);
        $isValidate = ContactValidator::validate($contactData);
        if ($isValidate) {
            //Seleccionamos correo destino en función del asunto recibido, que debería estar en cfg.ini, si no, se activa el valor default
            $contactData['toEmail'] = $this->chooseToFromSubject($contactData['subject']);

            //Recogemos del cfg.ini la cuenta remitente de correo
            $cfgOriginEmailIni = $this->iniTool->getKeysAndValues("originEmail");
            $contactData['fromEmail'] = $cfgOriginEmailIni['email'];
            $contactData['fromPassword'] = $cfgOriginEmailIni['password'];

            if (EmailTool::sendEmail($contactData, "contactTemplate")) {
                return true;
            }
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
