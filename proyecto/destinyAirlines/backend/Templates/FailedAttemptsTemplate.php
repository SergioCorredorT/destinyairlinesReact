<?php
require_once "./images/isotipo_small_CSS.php";
require_once "./Tools/IniTool.php";
class FailedAttemptsTemplate
{
  static function applyFailedAttemptsTemplate($data)
  {
    $iniTool = new IniTool('./Config/cfg.ini');
    $aboutLogin = $iniTool->getKeysAndValues("aboutLogin");
    $maxFailedLoginAttemps = $aboutLogin['maxFailedLoginAttemps'];
    $generatedPasswordCharacters = $aboutLogin['generatedPasswordCharacters'];

    $newUserPassword = $data["newUserPassword"];
    $lastFailedAttempt = $data['lastFailedAttempt'];

    $subject = "subject";
    $userMessage = "";


    $isotipo_URL = "https://lh3.googleusercontent.com/pw/ADCreHcBgNwlq4KG-PxMPtJXEOCJZ7BD6pgXMgBvLWmA8qY0R1SkzjPMcASMurvjyp7pAcA4rngXW6yn0umyPjL72b9eO9RwavbVMHIArvmvutsjOodl8wnH4RH0XfOqY1COQAVV6qMyQOy1VqJ3Ur77PA=w200-h190-s-no?authuser=0";

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
          padding: 20px;
        }

        header {
          text-align: center;
        }

        .logo {
          width: 200px;
          margin: auto;
        }

        .header-contain {
          background-color: #f8f9fa;
          padding: 20px;
        }

        .header-contain td{
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
        }
        </style>
        </head>
        <body>
          <header>
            <div class="header-logo">
              <img class="logo" src="' . $isotipo_URL . '" alt="Logotipo" />
            </div>
            <div class="header-contain">
                <table>
                <tr>
                  <td>
                    Nombre:
                  </td>
                  <td>
                  ' . $userName . '
                  </td>
                </tr>
                <tr>
                  <td>
                    Email:
                  </td>
                  <td>
                  ' . $userEmail . '
                  </td>
                </tr>
                <tr>
                  <td>
                    Número de teléfono:
                  </td>
                  <td>
                  ' . $userPhoneNumber . '
                  </td>
                </tr>
                <tr>
                  <td>
                    Asunto:
                  </td>
                  <td>
                  ' . $subject . '
                  </td>
                </tr>
              </table>
            </div>
          </header>
          <main>
            <p class="mainText">' . $userMessage . '</p>
          </main>
          <footer>
              <p>' . $companyPhoneNumber . '</p>
              <p>' . $companyLegalInfo . '</p>
          </footer>
        </body>
      </html>';
  }
}
