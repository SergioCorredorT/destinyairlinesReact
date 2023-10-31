<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    $unblockToken = $_POST['unblockToken'];

    if ($new_password == $confirm_password) {
        try {
            require_once './Tools/TokenTool.php';
            $dedodedUnblockToken = TokenTool::decodeAndCheckToken($unblockToken);
            if ($dedodedUnblockToken) {
                $userId = $dedodedUnblockToken->data->id;
                require_once './Models/UserModel.php';
                $UserModel = new UserModel();
                //comprobar si el campo del email enviado es distinto de null
                if (!is_null($UserModel->readLastPasswordResetEmailSentAt($userId)[0]["lastPasswordResetEmailSentAt"])) {
                    //Reiniciamos el contador de intentos

                    $UserModel->updateResetFailedAttempts($userId);

                    //Insertamos en bbdd la nueva contraseña hasheada
                    $UserModel->updatePasswordHashById(password_hash($new_password, PASSWORD_BCRYPT), $userId);

                    //modificar a null el campo del email enviado de la tabla users
                    $UserModel->updateLastPasswordResetEmailSentAt("NULL", $userId);

                    echo '<span class="success">Contraseña cambiada con éxito, puede cerrar esta página.</span>';
                } else {
                    echo '<span class="warning">Contraseña ya fue cambiada en una anterior ocasión.</span>';
                }
            }
        } catch (Exception $er) {
            echo '<span class="error">Hubo un error en el cambio de contraseña.</span>';
            error_log('Catched exception: ' .  $er->getMessage() . "\n");
        }
    }
} elseif ($_GET) {
    $unblockTokenGet = $_GET['unblockToken'];
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
                height: 100vh;
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
            <p id="mensaje"></p>
            <label for="new_password">Nueva contraseña:</label>
            <input type="password" id="new_password" name="new_password">
            <label for="confirm_password">Confirma tu nueva contraseña:</label>
            <input type="password" id="confirm_password" name="confirm_password">
            <input type="hidden" value="<?php echo $unblockTokenGet; ?>" name="unblockToken">
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

                fetch('<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>', {
                    method: form.method,
                    body: formData
                }).then(response => {
                    if (response.ok) {
                        return response.text();
                    } else {
                        throw new Error('Error: ' + response.statusText);
                    }
                }).then(text => {
                    mensaje.innerHTML = text;
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
