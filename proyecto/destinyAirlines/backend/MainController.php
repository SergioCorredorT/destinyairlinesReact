<?php

$action = $_POST['action'] ?? "";

switch (strtolower($action)) {
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
//OJO, MODIFICAR PARA ELIMINAR EL QUE HAYA EN SESIÓN
            require_once './Controllers/UserController.php';
            try {
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


//AQUÍ EL CÓDIGO PARA LOGIN

                echo json_encode(['status' => true, 'response' => $rsp]);
            } catch (Exception $e) {
                echo json_encode(['status' => false, 'response' => $rsp]);
                //echo json_encode(['Catched error: ' . $e]);
                exit();
            }
            break;
        }
    default: {
            echo json_encode(['status' => true, 'response' => false]);
            break;
        }
}
