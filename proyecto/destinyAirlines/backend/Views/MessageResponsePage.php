<?php
if ($_SERVER['REQUEST_METHOD'] == 'GET' && $_GET) {
    $title = $_GET['title'] ?? '';
    $message = $_GET['message'] ?? '';
    $messageType = $_GET['messageType'] ?? '';
?>
    <!DOCTYPE html>
    <html>

    <head>
        <title><?php echo $title ?></title>
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
                display: grid;
                place-content: center;
                min-height: 100vh;
                min-width: 280px;
                background-color: rgb(32, 35, 37);
            }

            .messageContainer {
                background-color: rgb(24, 26, 27);
                padding: 20px;
                border-radius: 15px;
                box-shadow: 0px 0px 10px 5px rgba(0, 0, 0, 0.2);
                width: 250px;
                color: #fff;
            }

            .message {
                text-align: center;
            }

            .warning {
                color: rgb(240, 173, 78);
            }

            .error {
                color: rgb(204, 2, 2);
            }

            .success {
                color: rgb(40, 167, 69);
            }

            .warning,
            .error,
            .success {
                font-weight: 600;
            }
        </style>
    </head>

    <body>
        <div class="messageContainer">
            <p class="message <?php echo $messageType ?>"><?php echo $message ?></p>
        </div>
    </body>

    </html>

<?php
}
