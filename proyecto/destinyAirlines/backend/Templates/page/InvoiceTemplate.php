<?php
require_once "./Tools/IniTool.php";
require_once "./Templates/page/BaseTemplate.php";
class InvoicePageTemplate extends BaseTemplate
{
  static function applyInvoicePageTemplate(array $data)
  {
    //MODIFICAR
    $title = "Invoice";

    return "
<!DOCTYPE html>
<html lang='es'>
  <head>
    <title>Invoice</title>
    <meta charset='UTF-8' />
    <meta name='viewport' content='width=device-width, initial-scale=1.0' />
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
          'clientData     companyData'
          'invoiceData    invoiceData'
          'invoiceDetail  invoiceDetail'
          '.              invoiceTotals';
      }
      .clientData {
        grid-area: clientData;
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
    <div class='container'>
      <header>
        <section class='title'>
          <h1>Factura</h1>
        </section>
        <section class='logo'>
          <img
            alt='logo'
            src='https://lh3.googleusercontent.com/pw/ADCreHcBgNwlq4KG-PxMPtJXEOCJZ7BD6pgXMgBvLWmA8qY0R1SkzjPMcASMurvjyp7pAcA4rngXW6yn0umyPjL72b9eO9RwavbVMHIArvmvutsjOodl8wnH4RH0XfOqY1COQAVV6qMyQOy1VqJ3Ur77PA=w200-h190-s-no?authuser=0'
          />
        </section>
      </header>
      <main>
        <section class='clientData'>clientData</section>
        <section class='companyData'>companyData</section>
        <section class='invoiceData'>invoiceData</section>
        <section class='invoiceDetail'>invoiceDetail</section>
        <section class='invoiceTotals'>invoiceTotals</section>
      </main>
      <footer>
        <p>Gracias por confiar en Destiny Airlines</p>
      </footer>
    </div>
  </body>
</html>
    ";
  }
}
