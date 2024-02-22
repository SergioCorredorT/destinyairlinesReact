<?php
require_once ROOT_PATH . '/Templates/email/EmailBaseTemplate.php';
class ChangePasswordTemplate extends EmailBaseTemplate
{
  static function applyChangePasswordTemplate(array $data): string
  {
    $title = 'Change password';
    $subject = $data['subject'];
    
    $message = "Estimado usuario,

    <p>Queremos informarle de que su contraseña ha sido cambiada recientemente. Si ha realizado este cambio, no necesita hacer nada más.</p>

    <p>Si no ha solicitado un cambio de contraseña, le recomendamos que se ponga en contacto con nuestro equipo de soporte lo antes posible para garantizar la seguridad de su cuenta.</p>
    
    <p>Agradecemos su atención a este asunto y agradecemos su continuo apoyo.</p>
    
    <p>Atentamente,</p>
    <p>Sergio Corredor</p>
    <p>Director ejecutivo de Destiny Airlines</p>
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
