<?php
require_once './Tools/IniTool.php';
require_once './Templates/page/BaseTemplate.php';
class InvoicePageTemplate extends BaseTemplate
{
  static function applyInvoicePageTemplate(array $data)
  {
    require_once './Tools/IniTool.php';
    $iniTool = new IniTool('./Config/cfg.ini');
    $imageLinks = $iniTool->getKeysAndValues('imageLinks');
    $companyInfo = $iniTool->getKeysAndValues('companyInfo');
    return '
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
              "clientData     companyData"
              "invoiceData    invoiceData"
              "invoiceDetail  invoiceDetail"
              ".              invoiceTotals";
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
              <h2>invoiceDetail</h2>
              <div class="invoiceDetail-main">
                <table class="invoiceDetail-main-table">
                  <tr>
                    <th>Item</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total Price</th>
                  </tr>
                  <tr>
                    <td>
                      <p><strong>Flight from Madrid to New York</strong></p>
                    </td>
                    <td>$100</td>
                    <td>1</td>
                    <td>$100</td>
                  </tr>
                </table>
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
  }
}
