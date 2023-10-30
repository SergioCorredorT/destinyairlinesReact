<?php
$mensaje = '';
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

                //Reiniciamos el contador de intentos
                require_once './Models/UserModel.php';
                $UserModel = new UserModel();
                $UserModel->updateResetFailedAttempts($userId);

                //Insertamos en bbdd la nueva contraseña hasheada
                $UserModel->updatePasswordHashById("'" . password_hash($new_password, PASSWORD_BCRYPT) . "'", $userId);
                $mensaje = 'Contraseña cambiada con éxito.';
            }
        } catch (Exception $er) {
            error_log('Catched exception: ' .  $er->getMessage() . "\n");
        }
    }
} elseif ($_GET['$unblockToken']) {
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
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                background-color: #f0f0f0;
            }

            form {
                background-color: #fff;
                padding: 20px;
                border-radius: 5px;
                box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.1);
            }

            input[type="password"] {
                width: 100%;
                padding: 10px;
                margin-bottom: 10px;
                border-radius: 5px;
                border: 1px solid #ccc;
            }

            input[type="submit"] {
                width: 100%;
                padding: 10px;
                border-radius: 5px;
                border: none;
                color: #fff;
                background-color: #007BFF;
            }
        </style>
        <script>
            function validarFormulario() {
                var newPassword = document.getElementById('new_password').value;
                var confirmPassword = document.getElementById('confirm_password').value;

                // Validar que las contraseñas coinciden
                if (newPassword !== confirmPassword) {
                    alert('Las contraseñas no coinciden.');
                    return false;
                }

                // Longitud mínima y máxima
                if (password.length < 9 || password.length > 100) {
                    alert("La contraseña debe tener entre 9 y 100 caracteres.");
                    return false;
                }

                // Requerimientos de caracteres
                if (!/[a-z]/.test(password) || // Debe contener al menos una letra minúscula
                    !/[A-Z]/.test(password) || // Debe contener al menos una letra mayúscula
                    !/[0-9]/.test(password) || // Debe contener al menos un dígito
                    !/[\W_]/.test(password)) { // Debe contener al menos un carácter especial
                    alert("La contraseña debe contener al menos una letra minúscula, una letra mayúscula, un dígito y un carácter especial.");

                    return false;
                }
                return true;
            }
        </script>
    </head>

    <body>
        <?php if ($mensaje) : ?>
            <p><?php echo $mensaje; ?></p>
        <?php else : ?>
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" onsubmit="return validarFormulario();">
                <label for="new_password">Nueva contraseña:</label><br>
                <input type="password" id="new_password" name="new_password"><br>
                <label for="confirm_password">Confirma tu nueva contraseña:</label><br>
                <input type="password" id="confirm_password" name="confirm_password"><br>
                <input type="hidden" value="<?php echo $unblockTokenGet; ?>" name="unblockToken">
                <input type="submit" value="Cambiar contraseña">
            </form>
        <?php endif; ?>
    </body>

    </html>
<?php
}
