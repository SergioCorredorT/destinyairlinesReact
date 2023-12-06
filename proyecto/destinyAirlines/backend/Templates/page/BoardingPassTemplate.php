<?php
//Recuerda crear qr

require_once './Tools/IniTool.php';
require_once './Templates/page/PageBaseTemplate.php';
class BoardingPassPageTemplate extends PageBaseTemplate
{
  static function  applyBoardingPassPageTemplate(array $data)
  {
    require_once './Tools/IniTool.php';
    $iniTool = new IniTool('./Config/cfg.ini');
    $imageLinks = $iniTool->getKeysAndValues('imageLinks');
    $companyInfo = $iniTool->getKeysAndValues('companyInfo');

    $bookCode = $data['bookCode'];
    $flightDate = $data['flightDate'];
    $flightHour = $data['flightHour'];
    $origin = $data['originAirportName'];
    $destiny = $data['destinyAirportName'];
    $passengersData = $data['passengersData'];


    


    $html = '
    <!DOCTYPE html>
    <html lang="es">
      <head>
        <title>BoardingPass</title>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <style>
          :root {
            --div-bg-color: #e6e6e6;
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
    
          header {
            display: grid;
          }
    
          .title {
            text-align: center;
          }
    
          .title h1 {
            margin: 0;
          }
    
          .logo {
            display: flex;
          }
          .logo img {
            height: 150px;
            margin-left: auto;
          }
    
          main {
            display: grid;
            grid-template-columns: 1fr 1fr;
            grid-template-rows: auto;
            row-gap: 20px;
            column-gap: 10px;
            grid-template-areas:
              "clientData                       companyData"
              "invoiceData                      invoiceData"
              "invoiceDetail                    invoiceDetail"
              "invoiceEspecialDiscountsDetail   invoiceEspecialDiscountsDetail"
              "invoiceServicesDetail            invoiceServicesDetail"
              ".                                invoiceTotals";
          }
    
          section {
            padding: 20px;
          }
    
          .clientData {
            grid-area: clientData;
          }
          .negrita {
            font-weight: bold;
          }
    
          .clientData-main-line, .companyData-main-line, .invoiceTotals {
            display: flex;
            justify-content: space-between;
          }

          .companyData {
            grid-area: companyData;
          }

          .invoiceData {
            grid-area: invoiceData;
          }

          .invoiceDetail {
            grid-area: invoiceDetail;
          }
    
          .invoiceDetail-main-table {
            width: 100%;
          }

          .invoiceDetail-main-table td {
            text-align: center;
          }
    
          .invoiceEspecialDiscountsDetail {
            grid-area: invoiceEspecialDiscountsDetail;
          }

          .invoiceServicesDetail {
            grid-area: invoiceServicesDetail;
          }

          .invoiceTotals {
            grid-area: invoiceTotals;
          }
          footer {
            text-align: center;
            padding: 10px;
          }
    
          main section,
          footer {
            background-color: var(--div-bg-color);
          }
        </style>
      </head>
      <body>
        <div class="container">
          <header>
            <section class="title">
              <h1>Tarjetas de embarque</h1>
            </section>
            <div class="logo">
              <img
                alt="logo"
                src="'.$imageLinks['isotipo'].'"
              />
            </div>
          </header>
          <main>

          </main>
          <footer>
            <p>Gracias por confiar en Destiny Airlines</p>
          </footer>
        </div>
      </body>
    </html> 
    ';
    return $html;
  }
}
