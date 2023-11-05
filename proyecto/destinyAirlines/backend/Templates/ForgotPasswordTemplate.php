<?php
require_once './Tools/IniTool.php';
require_once './Templates/BaseTemplate.php';
class ForgotPasswordTemplate extends BaseTemplate
{
  static function applyForgotPasswordTemplate($data)
  {
    $title = 'Forgot password';
    $forgotPasswordLink = $data['forgotPasswordLink'];
    $subject = $data['subject'];

    $message = "Estimado usuario,

    <p>Hemos recibido una petición de cambio de contraseña debido a su olvido.</p>
    
    <p>Puede introducir una nueva contraseña de acceso en el siguiente enlace: '$forgotPasswordLink'</p>

    <p>Si no ha hecho una petición de cambio de contraseña debido a su olvido y cree que alguien más podría estar intentando acceder a su cuenta, por favor, póngase en contacto con nuestro servicio de atención al cliente.</p>

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
