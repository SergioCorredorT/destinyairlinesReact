<?php
$command = $_POST['command'] ?? "";

switch (strtolower($command)) {
    case 'contact': {
            require_once './Controllers/ContactController.php';

            try {
                $contactController = new ContactController();
                $rsp = $contactController->sendContact($_POST);
                //status indica si hubo exception, response indica el resultado de la operación
                echo json_encode(['status' => true, 'response' => $rsp]);
            } catch (Exception $e) {
                echo json_encode(['status' => false, 'response' => $rsp]);
                //echo json_encode(['Catched error: ' . $e]);
                exit();
            }
            break;
        }
    case 'createuser': {
            require_once './Controllers/UserController.php';
            try {
                //Comprobar token
                $userController = new UserController();
                $rsp = $userController->createUser($_POST);
                echo json_encode(['status' => true, 'response' => $rsp]);
            } catch (Exception $e) {
                echo json_encode(['status' => false, 'response' => $rsp]);
                //echo json_encode(['Catched error: ' . $e]);
                exit();
            }
            break;
        }

    case 'removeuser': {
            require_once './Controllers/UserController.php';
            try {
                //Comprobar token
                $userController = new UserController();
                $rsp = $userController->deleteUser($_POST);
                echo json_encode(['status' => true, 'response' => $rsp]);
            } catch (Exception $e) {
                echo json_encode(['status' => false, 'response' => $rsp]);
                //echo json_encode(['Catched error: ' . $e]);
                exit();
            }
            break;
        }

    case 'loginuser': {
            require_once './Controllers/UserController.php';
            try {
                $UserController = new UserController();
                $rsp = $UserController->loginUser($_POST);
                echo json_encode(['status' => true, 'response' => $rsp]);
            } catch (Exception $e) {
                echo json_encode(['status' => false, 'response' => $rsp]);
                //echo json_encode(['Catched error: ' . $e]);
                exit();
            }
            break;
        }
    case 'logoutuser': {
            require_once './Controllers/UserController.php';
            try {
                //no funca
                $UserController = new UserController();
                $rsp = $UserController->logoutUser($_POST["token"]);
                echo json_encode(['status' => true, 'response' => $rsp]);
            } catch (Exception $e) {
                echo json_encode(['status' => false, 'response' => $rsp]);
                //error_log(json_encode(['Catched error: ' . $e]),0);
                exit();
            }
            break;
        }

    default: {
            echo json_encode(['status' => true, 'response' => false]);
            break;
        }
}
