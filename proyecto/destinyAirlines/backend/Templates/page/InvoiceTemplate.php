<?php
require_once './Tools/IniTool.php';
require_once './Templates/page/BaseTemplate.php';
class InvoicePageTemplate extends BaseTemplate
{
  static function  applyInvoicePageTemplate(array $data)
  {
    require_once './Tools/IniTool.php';
    $iniTool = new IniTool('./Config/cfg.ini');
    $imageLinks = $iniTool->getKeysAndValues('imageLinks');
    $companyInfo = $iniTool->getKeysAndValues('companyInfo');
    $priceSettings = $iniTool->getKeysAndValues('priceSettings');
    $adultsNumber = intval($data['bookData']['adultsNumber']);
    $childsNumber = intval($data['bookData']['childsNumber']);
    $infantsNumber = intval($data['bookData']['infantsNumber']);
    $firstCategory = true;
    $totalCategories = ($adultsNumber > 0) + ($childsNumber > 0) + ($infantsNumber > 0);
    $adultDiscountPercentage = intval($priceSettings['adultDiscountPercentage']);
    $childDiscountPercentage = intval($priceSettings['childDiscountPercentage']);
    $infantDiscountPercentage = intval($priceSettings['infantDiscountPercentage']);
    $totalServicesPrice = 0;
    $discounts = $data['discounts'];

    $html = '
    <!DOCTYPE html>
    <html lang="es">
      <head>
        <title>Invoice</title>
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
          .clientData-main {
    
          }
    
          .clientData-main-line, .companyData-main-line {
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
              <h1>Factura</h1>
            </section>
            <div class="logo">
              <img
                alt="logo"
                src="'.$imageLinks['isotipo'].'"
              />
            </div>
          </header>
          <main>
            <section class="clientData">
              <h2>Datos del cliente</h2>
              <div class="clientData-main">
                <div class="clientData-main-line">
                  <span>'.$data['userData']['documentationType'].'</span> <span>'.$data['userData']['documentCode'].'</span>
                </div>
                <div class="clientData-main-line">
                  <span>First Name:</span> <span>'.$data['userData']['firstName'].'</span>
                </div>
                <div class="clientData-main-line">
                  <span>Last Name:</span> <span>'.$data['userData']['lastName'].'</span>
                </div>
                <div class="clientData-main-line">
                  <span>Zip code:</span> <span>'.$data['userData']['zipCode'].'</span>
                </div>
                <div class="clientData-main-line">
                  <span>Email:</span> <span>'.$data['userData']['emailAddress'].'</span>
                </div>
              </div>
            </section>
            <section class="companyData">
              <h2>Datos de la empresa</h2>
              <div class="companyData-main">
                <div class="companyData-main-line">
                  <span>Name:</span> <span>'.$companyInfo['airlineName'].'</span>
                </div>
                <div class="companyData-main-line">
                  <span>Dirección:</span> <span>'.$companyInfo['airlineAddress'].'</span>
                </div>
                <div class="companyData-main-line">
                  <span>Teléfono:</span> <span>'.$companyInfo['phoneNumber'].'</span>
                </div>
                <div class="companyData-main-line">
                  <span>Email:</span> <span>'.$companyInfo['phoneNumber'].'</span>
                </div>
              </div>
            </section>
            <section class="invoiceData">Factura: '.$data['invoiceData']['invoiceCode'].', '.$data['invoiceData']['invoicedDate'].'. Reserva: '.$data['bookData']['bookCode'].', '.$data['bookData']['direction'].'</section>
            <section class="invoiceDetail">
              <div class="invoiceDetail-main">
                <h2>Detalles de pasajeros</h2>
                <table class="invoiceDetail-main-table">
                  <tr>
                    <th>Viaje</th>
                    <th>Concepto</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Descuento</th>
                    <th>Subtotal</th>
                  </tr>';
                  if($totalCategories > 0) {
                    $adultsPrice = (floatval($data['flightData']['price'])*$adultsNumber) * (1 - $adultDiscountPercentage/100);
                    $childrenPrice = (floatval($data['flightData']['price'])*$childsNumber) * (1 - $childDiscountPercentage/100);
                    $infantsPrice = (floatval($data['flightData']['price'])*$infantsNumber) * (1 - $infantDiscountPercentage/100);
                    $flightTd=
                    '<td rowspan=\''.$totalCategories.'\'>
                      <p>Vuelo '.$data['airportOriginData']['name'].' ('.$data['airportOriginData']['IATA'].') a <br> '.$data['airportDestinyData']['name'].' ('.$data['airportDestinyData']['IATA'].')</p>
                    </td>';

                    if($adultsNumber>0) {
                      $html.=
                      '<tr>';
                        if($firstCategory) {
                          $html.=$flightTd;
                          $firstCategory=false;
                        }
                        $html.=
                        '<td>
                          Adultos
                        </td>
                        <td>'.$data['flightData']['price'].'</td>
                        <td>'.$adultsNumber.'</td>
                        <td>'.$adultDiscountPercentage.'</td>
                        <td>'.$adultsPrice.'</td>
                      </tr>';
                    }
                    if($childsNumber>0) {
                      $html.=
                      '<tr>';
                        if($firstCategory) {
                          $html.=$flightTd;
                          $firstCategory=false;
                        }
                        $html.=
                        '<td>
                          Niños
                        </td>
                        <td>'.$data['flightData']['price'].'</td>
                        <td>'.$childsNumber.'</td>
                        <td>'.$childDiscountPercentage.'</td>
                        <td>'.$childrenPrice.'</td>
                      </tr>';
                    }
                    if($infantsNumber>0) {
                      $html.=
                      '<tr>';
                        if($firstCategory) {
                          $html.=$flightTd;
                          $firstCategory=false;
                        }
                        $html.=
                        '<td>
                          Bebés
                        </td>
                        <td>'.$data['flightData']['price'].'</td>
                        <td>'.$infantsNumber.'</td>
                        <td>'.$infantDiscountPercentage.'</td>
                        <td>'.$infantsPrice.'</td>
                      </tr>';
                    }
                    $html.=
                    '<tr>
                      <td colspan="5">
                        Total:
                      </td>
                      <td>'.($adultsPrice+$childrenPrice+$infantsPrice).'</td>
                    </tr>';
                  }
                  $html.='
                </table>
              </div>
            </section>';

            if(!empty($discounts)) {
              $html.=
              '<section class="invoiceEspecialDiscountsDetail">
              <div class="invoiceDetail-main">
                <h2>Detalles de descuentos</h2>
                <table class="invoiceDetail-main-table">
                  <tr>
                    <th>Concepto</th>
                    <th>Descuento</th>
                    <th>Subtotal</th>
                  </tr>';
                  foreach ($data['discounts'] as $discount) {
                    $html.=
                    '<tr>
                      <td>'.$discount['name'].'</td>
                      <td>'.$discount['price'].'</td>
                      <td>Aquí el precio aplicando %, o quizás hacer % de % y después hacer cuenta total</td>
                    </tr>';
                  }
              $html.=
                    '<tr>
                      <td colspan="2">Total con descuentos aplicados: </td>
                      <td>1111111111</td>
                    </tr>
                  </table>
                </div>
              </section>';
            }

            
            if(!empty($data['services'])) {
              $html.=
              '<section class="invoiceServicesDetail">
              <div class="invoiceDetail-main">
                <h2>Detalles de servicios</h2>
                <table class="invoiceDetail-main-table">
                  <tr>
                    <th>Concepto</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Acción</th>
                    <th>Subtotal</th>
                  </tr>';
              foreach ($data['services'] as $service) {
                $totalServicesPrice += floatval($service['price']) * floatval($service['count']);
                $html.=
                '<tr>
                  <td>'.$service['name'].'</td>
                  <td>'.$service['price'].'</td>
                  <td>'.$service['count'].'</td>
                  <td>'.$service['addRemove'].'</td>
                  <td>'.(floatval($service['price']) * floatval($service['count'])).'</td>
                </tr>';
              }
              $html.=
                '<tr>
                  <td colspan="4">Total: </td>
                  <td>'.$totalServicesPrice.'</td>
                </tr>
              </table>';
            }

            $html.='
              </div>
            </section>

            <section class="invoiceTotals">invoiceTotals</section>
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
