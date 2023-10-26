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
                echo json_encode(['status' => false, 'response' => false]);
                error_log('Catched error: ' . $e);
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
                echo json_encode(['status' => false, 'response' => false]);
                error_log('Catched error: ' . $e);
            }
            break;
        }
    case 'updateuser': {
            require_once './Controllers/UserController.php';
            try {
                $userController = new UserController();
                $rsp = $userController->updateUser($_POST);
                echo json_encode(['status' => true, 'response' => $rsp]);
            } catch (Exception $e) {
                echo json_encode(['status' => false, 'response' => false]);
                error_log('Catched error: ' . $e);
            }
            break;
        }
    case 'removeuser': {
            require_once './Controllers/UserController.php';
            try {
                $userController = new UserController();
                $rsp = $userController->deleteUser($_POST);
                echo json_encode(['status' => true, 'response' => $rsp]);
            } catch (Exception $e) {
                echo json_encode(['status' => false, 'response' => false]);
                error_log('Catched error: ' . $e);
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
                echo json_encode(['status' => false, 'response' => false]);
                error_log('Catched error: ' . $e);
            }
            break;
        }
    case 'logoutuser': {
            require_once './Controllers/UserController.php';
            try {
                $UserController = new UserController();
                $rsp = $UserController->logoutUser($_POST);
                echo json_encode(['status' => true, 'response' => $rsp]);
            } catch (Exception $e) {
                echo json_encode(['status' => false, 'response' => false]);
                error_log('Catched error: ' . $e);
            }
            break;
        }

    default: {
            echo json_encode(['status' => true, 'response' => false]);
            break;
        }
}