<?php
//enviar correo o whatsapp al correo de empresa
require_once './Controllers/BaseController.php';
require_once './Validators/ContactSanitizerValidator.php';

class ContactController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function sendContact()
    {        
        $contactData = [
            'name'      => isset($_POST['name'])      ? ContactSanitizerValidator::sanitizeName($_POST['name'])         : null,
            'email'     => isset($_POST['email'])     ? ContactSanitizerValidator::sanitizeEmail($_POST['email'])       : null,
            'subject'   => isset($_POST['subject'])   ? ContactSanitizerValidator::sanitizeSubject($_POST['subject'])   : null,
            'message'   => isset($_POST['message'])   ? ContactSanitizerValidator::sanitizeMessage($_POST['message'])   : null,
            'dateTime'  => date('Y-m-d H:i:s')
        ];

        if (!empty(ContactSanitizerValidator::validate($contactData))) {
            // Dar respuesta al front
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Los datos del formulario no son válidos.']);
            exit();
        } else {
            // Continuar con el procesamiento
        }
    }
}
