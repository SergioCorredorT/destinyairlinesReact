<?php
//Recuerda crear qr

require_once ROOT_PATH . '/Tools/IniTool.php';
require_once ROOT_PATH . '/Tools/QrTool.php';
require_once ROOT_PATH . '/Templates/page/PageBaseTemplate.php';
class BoardingPassPageTemplate extends PageBaseTemplate
{
  static function  applyBoardingPassPageTemplate(array $data): string
  {
    $qrTool = new QrTool();
    $iniTool = new IniTool(ROOT_PATH  . '/Config/cfg.ini');
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
        body {
          padding: 0;
          margin: 0;
        }
        .container {
          display: block;
          gap: 20px;
          min-height: 100dvh;
        }
  
        header {
          display: block;
        }
  
        main {
          float: left;
          display: block;
          width: 100%;
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
          margin: 20px auto;
          display: block;
          width: 18cm;
          height: 10cm;
          page-break-after: always;
        }
  
        .boardingPass p {
          margin: 3px 0;
        }
  
        .boardingPassClient-main, .boardingPassCompany-main {
          align-items: center;
        }
  
        .boardingPassHeader {
          
        }
  
        .boardingPassClient {
          float: left;
          width: 12cm;
          height: 100%;
          outline: 1px solid #000;
          border-radius: 30px;
        }
  
        .boardingPassClient-header h2, .boardingPassCompany-header h2 {
          margin: 0;
          font-size: 0.4cm;
          width: 100%;
        }
  
        .boardingPassClient-main {
          height: 8cm;
        }
  
        .boardingPassClient-header {
          background: linear-gradient(to right, #ffffff 0%, #e0e0e0 100%);
          text-align: center;
          place-content: center;
          border-radius: 30px 30px 0 0;
          height: 1cm;
        }
  
        .boardingPassClient-qr {
          float: right;
          margin-right: 1cm;
        }

        .qr {
          width: 2cm;
          height: 2cm;
        }
  
        .boardingPassClient-name {
          margin-left: 1cm;
        }
  
        .boardingPassClient-dateHour {
          margin-left: 1cm;
        }
  
        .boardingPassClient-origin {
          margin-left: 1cm;
        }
  
        .boardingPassClient-destiny {
          margin-left: 1cm;
        }
  
        .boardingPassClient-flightCode {
          margin-left: 1cm;
        }
  
        .boardingPassClient-passengerCode {
          margin-left: 1cm;
        }
  
        .boardingPassClient-footer {
          background: linear-gradient(to right, #ffffff 0%, #e0e0e0 100%);
          border-radius: 0 0 30px 30px;
          height: 1cm;
        }
  
        .boardingPassCompany {
          float: left;
          width: 6cm;
          height: 100%;
          outline: 1px solid #000;
          border-radius: 30px;
        }
  
        .boardingPassCompany-main {
          height: 8cm;
        }
  
        .boardingPassCompany-header {
          background: linear-gradient(to right, #ffffff 0%, #e0e0e0 100%);
          text-align: center;
          border-radius: 30px 30px 0 0;
          height: 1cm;
        }
  
        .boardingPassCompany-name {
          margin-left: 1cm;
        }
  
        .boardingPassCompany-dateHour {
          margin-left: 1cm;
        }
  
        .boardingPassCompany-origin {
          margin-left: 1cm;
        }
  
        .boardingPassCompany-destiny {
          margin-left: 1cm;
        }
  
        .boardingPassCompany-flightCode {
          margin-left: 1cm;
        }
  
        .boardingPassCompany-passengerCode {
          margin-left: 1cm;
        }
  
        .boardingPassCompany-footer {
          background: linear-gradient(to right, #ffffff 0%, #e0e0e0 100%);
          border-radius: 0 0 30px 30px;
          height: 1cm;
        }
  
        footer {
          text-align: center;
          padding: 10px;
        }
  
        main section,
        footer {
          background-color: #ffff;
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
                    <div class="boardingPassClient-name"><p class="negrita">Nombre:</p><p>'.$firstName.' '.$lastName.'</p></div>
                    <div class="boardingPassClient-dateHour"><p class="negrita">Fecha/hora</p><p>'.$flightDate.', '.$flightHour.'</p></div>
                    <div class="boardingPassClient-origin"><p class="negrita">Desde: </p><p>'.$origin.'</p></div>
                    <div class="boardingPassClient-destiny"><p class="negrita">Hasta: </p><p>'.$destiny.'</p></div>
                    <div class="boardingPassClient-flightCode"><p class="negrita">Vuelo: </p><p>'.$flightCode.'</p></div>
                    <div class="boardingPassClient-passengerCode">'.$passengerCode.'</div>
                    <div class="boardingPassClient-qr"><img class="qr" src="'.$qrBase64AndDataUri.'"></div>
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
              </div>
              ';
            }
            $html .=
          '</div>
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
