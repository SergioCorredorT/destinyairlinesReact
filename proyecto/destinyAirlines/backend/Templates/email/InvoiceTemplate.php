<?php
require_once "./Templates/email/EmailBaseTemplate.php";
class InvoiceTemplate extends EmailBaseTemplate
{
  static function applyInvoiceTemplate(array $data): string
  {
    $title = "Invoice";
    $subject = $data["subject"];
    $message = '¡Gracias por elegir volar con Destiny Airlines! Confirmamos que hemos recibido su pago y su reserva está confirmada.

    Adjuntamos a este correo electrónico la factura de su viaje. Le recomendamos que la guarde para sus registros.
    
    Si tiene alguna pregunta o necesita más información, no dude en ponerse en contacto con nosotros.
    
    ¡Esperamos verle a bordo pronto!
    
    Saludos cordiales,
    El equipo de Destiny Airlines';

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
