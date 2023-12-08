<?php
//Recuerda crear qr

require_once './Tools/IniTool.php';
require_once './Tools/QrTool.php';
require_once './Tools/Html2ImgTool.php';
require_once './Templates/page/PageBaseTemplate.php';
class BoardingPassPageTemplate extends PageBaseTemplate
{
  static function  applyBoardingPassPageTemplate(array $data)
  {
    $qrTool = new QrTool();
    $iniTool = new IniTool('./Config/cfg.ini');
    $imageLinks = $iniTool->getKeysAndValues('imageLinks');
    $isotipo = $imageLinks['isotipoLocal'];
    $contenidoBinario = file_get_contents($isotipo);
    $isotipoBase64 = base64_encode($contenidoBinario);
  
    $flightCode = $data['flightCode'];
    $flightDate = $data['flightDate'];
    $flightHour = $data['flightHour'];
    $origin = $data['origin'];
    $destiny = $data['destiny'];
    $passengersData = $data['passengersData'];//firstName, lastName, passengerCode

    $html = '
    <!DOCTYPE html>
    <html lang="es">
      <head>
        <title>BoardingPass</title>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <style>
          :root {
            --div-bg-color: #ffff;
          }

          * {
            box-sizing: border-box;
          }

          body {
            padding: 0;
            margin: 0;
          }

          .container {
            display: grid;
            row-gap: 20px;
            padding: 50px;
            min-height: 100dvh;
            grid-template-columns: 1fr;
          }

          .header {
            display: grid;
          }

          .main {
            display: grid;
            grid-template-columns: 1fr;
            row-gap: 20px;
          }

          .waterMark {
            background: linear-gradient(
                rgba(255, 255, 255, 0.5),
                rgba(255, 255, 255, 0.5)
              ),
              url("data:image/png;base64,'.$isotipoBase64.'");
            background-repeat: no-repeat;
            background-position: center;
            background-size: contain;
          }

          .boardingPass {
            aspect-ratio: 16 / 9;
            display: grid;
            grid-template-columns: 3fr 1fr;
            grid-template-rows: 1fr;
            column-gap: 2px;
            grid-template-areas:
              "boardingPassHeader                               boardingPassHeader"
              "boardingPassClient                               boardingPassCompany"
              "boardingPassFooter                               boardingPassFooter";
          }

          .boardingPass p {
            margin: 3px 0;
          }

          .boardingPassClient-main, .boardingPassCompany-main {
            align-items: center;
          }

          .boardingPassHeader {
            grid-area: boardingPassHeader;
          }

          .boardingPassClient {
            outline: 1px solid #000;
            border-radius: 30px;
            display: grid;
            grid-template-columns: 1fr;
            grid-template-rows: 50px 1fr 20px;
            row-gap: 20px;
            column-gap: 10px;
            grid-template-areas:
              "boardingPassClient-header"
              "boardingPassClient-main"
              "boardingPassClient-footer";
          }

          .boardingPassClient-header h2, .boardingPassCompany-header h2 {
            margin: 0;
            font-size: 20px;
          }

          .boardingPassClient-main {
            padding: 10px 50px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            row-gap: 20px;
            column-gap: 10px;
            grid-template-areas:
              "boardingPassClient-name                             boardingPassClient-dateHour"
              "boardingPassClient-origin                           boardingPassClient-flightCode"
              "boardingPassClient-destiny                          boardingPassClient-qr"
              "boardingPassClient-passengerCode                    boardingPassClient-qr";
          }

          .boardingPassClient-header {
            grid-area: boardingPassClient-header;
            background: linear-gradient(to right, #ffffff 0%, #e0e0e0 100%);
            text-align: center;
            display: grid;
            place-content: center;
            border-radius: 30px 30px 0 0;
          }

          .boardingPassClient-qr {
            grid-area: boardingPassClient-qr;
          }

          .boardingPassClient-name {
            grid-area: boardingPassClient-name;
          }

          .boardingPassClient-dateHour {
            grid-area: boardingPassClient-dateHour;
          }

          .boardingPassClient-origin {
            grid-area: boardingPassClient-origin;
          }

          .boardingPassClient-destiny {
            grid-area: boardingPassClient-destiny;
          }

          .boardingPassClient-flightCode {
            grid-area: boardingPassClient-flightCode;
          }

          .boardingPassClient-passengerCode {
            grid-area: boardingPassClient-passengerCode;
          }

          .boardingPassClient-footer {
            grid-area: boardingPassClient-footer;
            background: linear-gradient(to right, #ffffff 0%, #e0e0e0 100%);
            border-radius: 0 0 30px 30px;
          }

          .boardingPassCompany {
            outline: 1px solid #000;
            border-radius: 30px;
            display: grid;
            grid-template-columns: 1fr;
            grid-template-rows: 50px 1fr 20px;
            row-gap: 20px;
            column-gap: 10px;
            grid-template-areas:
              "boardingPassCompany-header"
              "boardingPassCompany-main"
              "boardingPassCompany-footer";
          }

          .boardingPassCompany-main {
            padding: 20px;
            display: grid;
            grid-template-columns: 1fr;
            row-gap: 20px;
            grid-template-areas:
              "boardingPassCompany-name"
              "boardingPassCompany-origin"
              "boardingPassCompany-destiny"
              "boardingPassCompany-dateHour"
              "boardingPassCompany-flightCode"
              "boardingPassCompany-passengerCode";
          }

          .boardingPassCompany-header {
            grid-area: boardingPassCompany-header;
            background: linear-gradient(to right, #ffffff 0%, #e0e0e0 100%);
            text-align: center;
            display: grid;
            place-content: center;
            border-radius: 30px 30px 0 0;
          }

          .boardingPassCompany-name {
            grid-area: boardingPassCompany-name;
          }

          .boardingPassCompany-dateHour {
            grid-area: boardingPassCompany-dateHour;
          }

          .boardingPassCompany-origin {
            grid-area: boardingPassCompany-origin;
          }

          .boardingPassCompany-destiny {
            grid-area: boardingPassCompany-destiny;
          }

          .boardingPassCompany-flightCode {
            grid-area: boardingPassCompany-flightCode;
          }

          .boardingPassCompany-passengerCode {
            grid-area: boardingPassCompany-passengerCode;
          }

          .boardingPassCompany-footer {
            grid-area: boardingPassCompany-footer;
            background: linear-gradient(to right, #ffffff 0%, #e0e0e0 100%);
            border-radius: 0 0 30px 30px;
          }

          .footer {
            text-align: center;
            padding: 10px;
          }

          .main .section,
          .footer {
            background-color: var(--div-bg-color);
          }

          .negrita {
            font-weight: bold;
          }
        </style>
      </head>
      <body>
        <div class="container">
          <div class="header">
            <div class="title section">
              <h1>Tarjetas de embarque</h1>
            </div>
          </div>
          <div class="main">';
            foreach($passengersData as $passengerData) {
              $firstName = $passengerData['firstName'];
              $lastName = $passengerData['lastName'];
              $passengerCode = $passengerData['passengerCode'];
              $qrBase64AndDataUri = $qrTool->generarQR($passengerCode);
              $html .=
                '<div class="article boardingPass">
                <div class="boardingPassClient">
                  <div class="boardingPassClient-header"><h2>Tarjeta de embarque - Destiny Airlines</h2></div>
                  <div class="boardingPassClient-main waterMark">
                  <div class="boardingPassClient-qr"><img class="qr" src="'.$qrBase64AndDataUri.'"></div>
                    <div class="boardingPassClient-name"><p class="negrita">Nombre:</p><p>'.$firstName.' '.$lastName.'</p></div>
                    <div class="boardingPassClient-dateHour"><p class="negrita">Fecha/hora</p><p>'.$flightDate.', '.$flightHour.'</p></div>
                    <div class="boardingPassClient-origin"><p class="negrita">Desde: </p><p>'.$origin.'</p></div>
                    <div class="boardingPassClient-destiny"><p class="negrita">Hasta: </p><p>'.$destiny.'</p></div>
                    <div class="boardingPassClient-flightCode"><p class="negrita">Vuelo: </p><p>'.$flightCode.'</p></div>
                    <div class="boardingPassClient-passengerCode">'.$passengerCode.'</div>
                  </div>
                  <div class="boardingPassClient-footer"></div>
                </div>
                <div class="boardingPassCompany">
                <div class="boardingPassCompany-header"><h2>Tarjeta de embarque - Destiny Airlines</h2></div>
                  <div class="boardingPassCompany-main">
                    <div class="boardingPassCompany-name"><p class="negrita">Nombre: </p><p>'.$firstName.' '.$lastName.'</p></div>
                    <div class="boardingPassCompany-origin"><p class="negrita">Desde: </p><p>'.$origin.'</p></div>
                    <div class="boardingPassCompany-destiny"><p class="negrita">Hasta: </p><p>'.$destiny.'</p></div>
                    <div class="boardingPassCompany-dateHour"><p class="negrita">Fecha/hora: </p><p>'.$flightDate.', '.$flightHour.'</p></div>
                    <div class="boardingPassCompany-flightCode"><p class="negrita">Vuelo: </p><p>'.$flightCode.'</p></div>
                    <div class="boardingPassCompany-passengerCode">'.$passengerCode.'</div>
                  </div>
                  <div class="boardingPassCompany-footer"></div>
                  </div>
              </div>';
            }
            $html .='</div>
          <div class="footer">
            <p>Gracias por confiar en Destiny Airlines</p>
          </div>
        </div>
      </body>
    </html>
    ';
    return $html;
  }
}
