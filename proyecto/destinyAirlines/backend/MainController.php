<?php

$action = $_POST['action'] ?? "";
//PARA PRUEBAS
//$action = 'createUser';

switch (strtolower($action)) {
    case 'contact': {
            require_once './Controllers/ContactController.php';

            /*
//PARA PRUEBAS
        $_POST = [
            'name'      => "Sergio",
            'email'     => "waa@gmail.com",
            'phoneNumber'=> "111223344",
            'subject'   => "motivazo bueno",
            'message'   => "Mensaje guauuuuuuuuuuuuuuu",
            'dateTime'  => date('Y-m-d H:i:s')
        ];

*/
            try {
                $contactController = new ContactController();
                $rsp = $contactController->sendContact($_POST);
                //status indica si hubo exception, response indica el resultado de la operación
                echo json_encode(['status' => true, 'response' => $rsp]);
            } catch (Exception $e) {
                echo json_encode(['Catched error: ' . $e]);
                exit();
            }
            break;
        }
    case 'createuser': {
            require_once './Controllers/UserController.php';
            try {
                $userController = new UserController();
                $rsp = $userController->createUser($_POST);
            } catch (Exception $e) {
                echo json_encode(['Catched error: ' . $e]);
                exit();
            }
            break;
        }
    case 'loginUser': {
            require_once './Controllers/UserController.php';
            try {
                $UserController = new UserController();
                $rsp = $UserController->getUserByEmailPassword($_POST);

                


            } catch (Exception $e) {
                echo json_encode(['Catched error: ' . $e]);
                exit();
            }
            break;
        }
    default: {

            break;
        }
}
