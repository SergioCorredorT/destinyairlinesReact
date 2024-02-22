<?php
require_once ROOT_PATH . '/Templates/email/EmailBaseTemplate.php';
class EmailVerificationTemplate extends EmailBaseTemplate
{
  static function applyEmailVerificationTemplate(array $data): string
  {
    $title = 'Email Verification';
    $emailVerificationLink = $data['emailVerificationLink'];
    $accountDeletionLink = $data['accountDeletionLink'];
    $subject = $data['subject'];
    
    $message = "Estimado usuario,
    
    <p>¡Gracias por registrarse en Destiny Airlines!</p>
    
    <p>Para completar su registro y activar su cuenta, por favor haga clic en el siguiente enlace: '$emailVerificationLink'</p>
    
    <p>Si no ha solicitado la creación de una cuenta en Destiny Airlines y cree que alguien más podría estar intentando usar su dirección de correo electrónico, puede eliminar la cuenta no activada haciendo clic en el siguiente enlace: '$accountDeletionLink'</p>
    
    <p>Si necesita ayuda adicional, por favor, póngase en contacto con nuestro servicio de atención al cliente.</p>
    
    <p>Agradecemos su confianza en nosotros.</p>
    
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
