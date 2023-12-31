<?php
require_once "./Tools/IniTool.php";
abstract class  EmailBaseTemplate
{
  static function getFooterContent(): string
  {
    $iniTool = new IniTool(ROOT_PATH  . '/Config/cfg.ini');
    $companyInfo = $iniTool->getKeysAndValues("companyInfo");
    $companyPhoneNumber = $companyInfo['phoneNumber'];
    $companyLegalInfo = $companyInfo['legalInfo'];
    return "
      <p>$companyPhoneNumber</p>
      <p>$companyLegalInfo</p>";
  }

  static function getHeaderLogo(): string
  {
    $iniTool = new IniTool(ROOT_PATH  . '/Config/cfg.ini');
    $imageLinks = $iniTool->getKeysAndValues("imageLinks");
    $isotipo_link = $imageLinks["isotipo"];
    return "<div class='header-logo'>
      <img class='logo' src='$isotipo_link' alt='Logotipo' />
    </div>";
  }

  static function getHeaderBody(string $subject): string
  {
    return "<div class='header-body'>
    <table>
      <tr>
        <td>
          Asunto:
        </td>
        <td>
          $subject
        </td>
      </tr>
    </table>
  </div>";
  }

  static function getHeaderContent(string $subject): string
  {
    return self::getHeaderLogo() . self::getHeaderBody($subject);
  }

  static function getHeadBaseContent(string $title): string
  {
    return "<meta charset='UTF-8' />
    <meta name='viewport' content='width=device-width, initial-scale=1.0' />
    <title>$title</title>";
  }

  static function getHeadContent(string $title): string
  {
    return self::getHeadBaseContent($title) . "
    <style>
      " . self::getBaseCSSContent() . "
    </style>";
  }

  static function getPMainText(string $message): string
  {
    return "<p class='mainText'>$message</p>";
  }

  static function getBaseCSSContent(): string
  {
    return "        
    body * {
      box-sizing: border-box;
    }

    body {
      font-family: Arial, sans-serif;
      padding: 20px;
    }

    header {
      text-align: center;
    }

    .logo {
      width: 200px;
      margin: auto;
    }

    .header-body {
      background-color: #f8f9fa;
      padding: 20px;
    }

    .header-body td{
      text-align:left;
      padding: 5px 20px;
    }

    main {
      padding: 20px;
      text-align: center;
      display: flex;
      justify-content: center;
      align-items: center;
    }
    .mainText
    {
      padding:20px;
    }
    footer {
      text-align: center;
      background-color: #f8f9fa;
      padding: 20px;
    }

    footer > div {
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 5px;
    }";
  }
}
