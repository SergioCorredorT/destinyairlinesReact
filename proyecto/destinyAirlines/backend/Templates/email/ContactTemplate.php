<?php
require_once "./Templates/email/EmailBaseTemplate.php";
class ContactTemplate extends EmailBaseTemplate
{
  static function applyEmailContactTemplate(array $data): string
  {
    $title = "Contact";
    $userName = $data["name"];
    $userEmail = $data["email"];
    $userPhoneNumber = $data["phoneNumber"];
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
          <table>
            <tr>
              <td>
                Nombre:
              </td>
              <td>
                $userName
              </td>
            </tr>
            <tr>
              <td>
                Email:
              </td>
              <td>
                $userEmail
              </td>
            </tr>
            <tr>
              <td>
                Número de teléfono:
              </td>
              <td>
                $userPhoneNumber
              </td>
            </tr>
          </table>
          ". parent::getPMainText($message) ."
        </main>
        <footer>"
          . parent::getFooterContent() .
        "</footer>
      </body>
    </html>";
  }
}
