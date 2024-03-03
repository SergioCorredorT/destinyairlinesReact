<?php
require_once ROOT_PATH . '/Templates/email/EmailBaseTemplate.php';
class WelcomeTemplate extends EmailBaseTemplate
{
  static function applyWelcomeTemplate(array $data): string
  {
    $title = 'Welcome';
    $subject = $data['subject'];
    
    $message = "Estimado usuario,

    <p>¡Nos complace darle la bienvenida a Destiny Airlines! Su cuenta ha sido creada con éxito.</p>
    
    <p>Si necesita ayuda para navegar por su nueva cuenta o si tiene alguna pregunta, no dude en ponerse en contacto con nuestro equipo de soporte. Estamos aquí para ayudarle.</p>
    
    <p>Agradecemos su elección de volar con Destiny Airlines y esperamos proporcionarle una experiencia de viaje excepcional.</p>
    
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
