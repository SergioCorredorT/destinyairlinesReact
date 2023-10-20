<?php
class ContactTemplate
{
  static function applyContactTemplate($data)
  {
    $userName = $data["name"];
    $userEmail = $data["email"];
    $userPhoneNumber = $data["phoneNumber"];
    $subject = $data["subject"];
    $userMessage = $data["message"];

    $isotipo = './images/isotipo.png';
    $isotipo_base64 = base64_encode(file_get_contents($isotipo));

    //Recogemos del cfg.ini lo del footer
    $iniTool = new IniTool('./Config/cfg.ini');
    $companyInfo = $iniTool->getKeysAndValues("companyInfo");
    $companyPhoneNumber = $companyInfo['phoneNumber'];
    $companyLegalInfo = $companyInfo['legalInfo'];
    return '
    <!DOCTYPE html>
    <html lang="es">
      <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Contact</title>
        <style>
          body * {
            box-sizing: border-box;
          }
          body {
            font-family: Arial, sans-serif;
          }
          header {
            text-align: center;
            padding: 20px;
            display: grid;
            grid-template-columns: 200px 1fr;
          }
          .logo {
            width: 200px;
            margin: auto;
          }
          .header-contain {
            display: grid;
            background-color: #f8f9fa;
            padding: 20px;
            grid-template-rows: auto;
          }
          .header-contain > div {
            display: flex;
            align-items: center;
            justify-content: center;
          }
          main {
            padding: 20px;
            text-align: center;
          }
          footer {
            text-align: center;
            background-color: #f8f9fa;
            padding: 20px;
          }
          @media (max-width: 600px) {
            header {
              grid-template-columns: 1fr;
            }
          }
        </style>
      </head>
      <body>
        <header>
          <div class="header-logo">
            <img class="logo" src="' . $isotipo_base64 . '" alt="Logotipo" />
          </div>
          <div class="header-contain">
            <div>Nombre: ' . $userName . '</div>
            <div>Email: ' . $userEmail . '</div>
            <div>Número de teléfono: ' . $userPhoneNumber . '</div>
            <div>Asunto: ' . $subject . '</div>
          </div>
        </header>
        <main>
          <p>' . $userMessage . '</p>
        </main>
        <footer>
            <p>' . $companyPhoneNumber . '</p>
            <p>' . $companyLegalInfo . '</p>
        </footer>
      </body>
    </html>';
  }
}
