<?php
require_once ROOT_PATH . '/Tools/IniTool.php';
require_once ROOT_PATH . '/Templates/email/EmailBaseTemplate.php';
class AccountDeletionTemplate extends EmailBaseTemplate
{
  static function applyAccountDeletionTemplate(array $data): string
  {
    $title = 'Account deletion';
    $subject = $data['subject'];
    
    $message = "Estimado usuario,

    <p>Lamentamos informarle que su cuenta con Destiny Airlines ha sido eliminada.</p>
    
    <p>Si cree que esto es un error o si no solicitó la eliminación de su cuenta, por favor, póngase en contacto con nuestro servicio de atención al cliente de inmediato.</p>
    
    <p>Pedimos disculpas por cualquier inconveniente que esto pueda haber causado. Valoramos a cada miembro de nuestra familia de Destiny Airlines y esperamos poder servirle nuevamente en el futuro.</p>
    
    <p>Gracias por su comprensión.</p>
    
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
