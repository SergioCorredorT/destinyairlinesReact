<?php
require_once './Controllers/ContactController.php';
$action = $_POST['action'];
//PARA PRUEBAS
//$action = 'contact';

switch ($action) {
    case 'contact':
/*
//PARA PRUEBAS
      $contactData = [
            'name'      => "Sergio",
            'email'     => "waa@gmail.com",
            'phone'     => "111223344",
            'subject'   => "motivazo bueno",
            'message'   => "Mensaje guauuuuuuuuuuuuuuu",
            'dateTime'  => date('Y-m-d H:i:s')
        ];
*/

//Comprobamos si cada post necesario es isset y si es nulo
        $contactData = [
            'name'      => $_POST['name'] ?? null,
            'email'     => $_POST['email'] ?? null,
            'phone'     => $_POST['phoneNumber'] ?? null,
            'subject'   => $_POST['subject'] ?? null,
            'message'   => $_POST['message'] ?? null,
            'dateTime'  => date('Y-m-d H:i:s')
        ];

        $errors = array_filter($contactData, 'is_null');

        if (empty($errors)) {
            $contactController = new ContactController();
            $contactController->sendContact($contactData);
        } else {
            echo json_encode(['error' => $errors]);
        }
        break;
    case '':
        # code...
        break;
    default:
        # code...
        break;
}
