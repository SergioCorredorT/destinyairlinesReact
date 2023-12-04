<?php
require_once "./Tools/IniTool.php";
require_once "./Templates/email/EmailBaseTemplate.php";
class InvoiceTemplate extends EmailBaseTemplate
{
  static function applyInvoiceTemplate(array $data)
  {
    $title = "Invoice";
    $subject = $data["subject"];
    $message = $data["message"];

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
