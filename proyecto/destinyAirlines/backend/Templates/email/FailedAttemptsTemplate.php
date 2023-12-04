<?php
require_once "./Tools/IniTool.php";
require_once "./Templates/email/EmailBaseTemplate.php";
class FailedAttemptsTemplate extends EmailBaseTemplate
{
  static function applyEmailFailedAttemptsTemplate(array $data)
  {
    $iniTool = new IniTool('./Config/cfg.ini');
    $aboutLogin = $iniTool->getKeysAndValues("aboutLogin");
    $maxLoginAttemps = $aboutLogin['maxLoginAttemps'];

    $title = "Failed attempts";
    $lastAttempt = $data['lastAttempt'];
    $passwordResetLink = $data['passwordResetLink'];
    $subject = $data["subject"];

    $message = "Estimado usuario,

    <p>Hemos detectado $maxLoginAttemps intentos fallidos de inicio de sesión en su cuenta siendo el última con fecha $lastAttempt. Por razones de seguridad, hemos bloqueado su cuenta.</p>
    
    <p>Puede desbloquear su cuenta accediendo al siguiente enlace: '$passwordResetLink'</p>

    <p>Si no ha intentado iniciar sesión recientemente y cree que alguien más podría estar intentando acceder a su cuenta, por favor, póngase en contacto con nuestro servicio de atención al cliente.</p>

    <p>Gracias por su comprensión.</p>

    <p>Atentamente,</p>
    <p>Sergio Corredor</p>
    <p>Director Ejecutivo de Destiny Airlines</p>
    ";

    return "
    <!DOCTYPE html>
    <html lang='es'>
      <head>
        " . parent::getHeadContent($title) . "
      </head>
      <body>
        <header>
        " . parent::getHeaderContent($subject) . "
        </header>
        <main>
        ". parent::getPMainText($message) ."
        </main>
        <footer>"
          . parent::getFooterContent() .
        "</footer>
      </body>
    </html>";
  }
}
