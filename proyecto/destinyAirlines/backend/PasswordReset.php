<?php
if ($_SERVER["REQUEST_METHOD"] == "GET" && $_GET) {
    $unblockTokenGet = $_GET['unblockToken'];
    $tempId = $_GET['tempId'];
?>
    <!DOCTYPE html>
    <html>

    <head>
        <title>Restablecer Contraseña</title>
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

            form {
                background-color: rgb(24, 26, 27);
                padding: 20px;
                border-radius: 15px;
                box-shadow: 0px 0px 10px 5px rgba(0, 0, 0, 0.2);
                width: 250px;
                color: #fff;
            }

            input[type="password"] {
                width: 100%;
                padding: 10px;
                margin-bottom: 10px;
                border-radius: 10px;
                border: 1px solid rgb(118, 110, 97);
                box-sizing: border-box;
                background-color: rgba(255, 255, 255, 0.01);
                color: #fff;
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

            input[type="submit"] {
                width: 100%;
                padding: 10px;
                border-radius: 5px;
                border: none;
                color: #fff;
                background-color: #007BFF;
                cursor: pointer;
            }

            input[type="password"]:hover {
                outline: 1px solid #007BFF;
            }

            input[type="submit"]:hover {
                background-color: rgb(0, 84, 174);
            }
        </style>
    </head>

    <body>
        <form onsubmit="validarFormulario(event);" method="post">
            <p id="mensaje"><?php
                            try {
                                require_once './Tools/TokenTool.php';
                                $dedodedUnblockToken = TokenTool::decodeAndCheckToken($unblockTokenGet, "unblock");
                                if (isset($dedodedUnblockToken["errorCode"])) {
                                    if ($dedodedUnblockToken["errorCode"] === 1) {
                                        echo '<span class="warning">Token caducado. 1Le hemos enviado un nuevo enlace de activación a su dirección de correo electrónico, por favor, no se demore mucho en acceder al enlace enviado para evitar su caducidad</span>';
                                        //poner a null el lastPasswordResetEmailSentAt de bbdd y haga algo (hacer post al login() o crear código aquí) para enviar nuevo token
                                    }
                                }
                            } catch (Exception $er) {
                                error_log('Catched exception: ' .  $er->getMessage() . "\n");
                            }
                            ?></p>
            <label for="new_password">Nueva contraseña:</label>
            <input type="password" id="new_password" name="new_password">
            <label for="confirm_password">Confirma tu nueva contraseña:</label>
            <input type="password" id="confirm_password" name="confirm_password">
            <input type="hidden" value="<?php echo $unblockTokenGet; ?>" name="unblockToken">
            <input type="hidden" value="<?php echo $tempId; ?>" name="tempId">
            <input type="hidden" value="passwordReset" name="command">
            <input type="submit" value="Cambiar contraseña">
        </form>
        <script>
            function validarFormulario(event) {
                event.preventDefault();

                let newPassword = document.getElementById('new_password').value;
                let confirmPassword = document.getElementById('confirm_password').value;
                let mensaje = document.getElementById("mensaje");

                // Validar que las contraseñas coinciden
                if (newPassword !== confirmPassword) {
                    mensaje.innerHTML = "<span class='error'>Las contraseñas no coinciden.</span>";
                    return;
                }

                // Longitud mínima y máxima
                if (newPassword.length < 9 || newPassword.length > 100) {
                    mensaje.innerHTML = "<span class='error'>La contraseña debe tener entre 9 y 100 caracteres.</span>";
                    return;
                }

                // Requerimientos de caracteres
                if (!/[a-z]/.test(newPassword) || // Debe contener al menos una letra minúscula
                    !/[A-Z]/.test(newPassword) || // Debe contener al menos una letra mayúscula
                    !/[0-9]/.test(newPassword) || // Debe contener al menos un dígito
                    !/[\W_]/.test(newPassword)) { // Debe contener al menos un carácter especial
                    mensaje.innerHTML = "<span class='error'>La contraseña debe contener al menos una letra minúscula, una letra mayúscula, un dígito y un carácter especial.</span>";
                    return;
                }

                // Si todas las validaciones son correctas, realiza la acción de envío manualmente
                var form = event.target;
                var formData = new FormData(form);

                fetch('MainController.php', {
                    method: form.method,
                    body: formData
                }).then(response => {
                    if (response.ok) {
                        return response.json(); // Cambia esto
                    } else {
                        throw new Error('Error: ' + response.statusText);
                    }
                }).then(data => { // Cambia 'text' por 'data'
                    mensaje.innerHTML = data.message;
                    // Accede a las propiedades del objeto JSON como data.propiedad
                }).catch(error => {
                    mensaje.innerHTML = '<span class="error">Hubo un error en el cambio de contraseña.</span>';
                    console.error('Error:', error);
                });

            }
        </script>
    </body>

    </html>
<?php

}
