<?php



$action = $_POST['action'] ?? "";
//PARA PRUEBAS
$action = 'createUser';

switch (strtolower($action)) {
    case 'contact': {
            require_once './Sanitizers/ContactSanitizer.php';
            require_once './Validators/ContactValidator.php';
            require_once './Controllers/ContactController.php';
            /*
//PARA PRUEBAS
        $contactData = [
            'name'      => "Sergio",
            'email'     => "waa@gmail.com",
            'phoneNumber'=> "111223344",
            'subject'   => "motivazo bueno",
            'message'   => "Mensaje guauuuuuuuuuuuuuuu",
            'dateTime'  => date('Y-m-d H:i:s')
        ];

*/
            //Comprobamos si cada post necesario es isset y si es nulo
            $contactData = [
                'name'          => $_POST['name'] ?? "",
                'email'         => $_POST['email'] ?? "",
                'phoneNumber'   => $_POST['phoneNumber'] ?? "",
                'subject'       => $_POST['subject'] ?? "",
                'message'       => $_POST['message'] ?? "",
                'dateTime'      => date('Y-m-d H:i:s')
            ];

            try {
                $contactData = ContactSanitizer::sanitize($contactData);
                ContactValidator::validate($contactData);

                $contactController = new ContactController();
                $contactController->sendContact($contactData);
            } catch (Exception $e) {
                echo json_encode(['Catched error: ' . $e]);
                exit();
            }
            break;
        }
    case 'createuser':
        require_once './Sanitizers/UserSanitizer.php';
        require_once './Validators/UserValidator.php';
        require_once './Controllers/UserController.php';

        $userData = [
            'title'                 => $_POST['title'] ?? "",
            'firstName'             => $_POST['firstName'] ?? "",
            'lastName'              => $_POST['lastName'] ?? "",
            'tonCity'               => $_POST['townCity'] ?? "",
            'streetAddress'         => $_POST['streetAddress'] ?? "",
            'zipCode'               => $_POST['zipCode'] ?? "",
            'country'               => $_POST['country'] ?? "",
            'emailAddress'          => $_POST['emailAddress'] ?? "",
            'password'              => $_POST['password'] ?? "",
            'phoneNumber1'          => $_POST['phoneNumber1	'] ?? "",
            'phoneNumber2'          => $_POST['phoneNumber2'] ?? "",
            'phoneNumber3'          => $_POST['phoneNumber3'] ?? "",
            'companyName'           => $_POST['companyName'] ?? "",
            'companyTaxNumber'      => $_POST['companyTaxNumber'] ?? "",
            'companyPhoneNumber'    => $_POST['companyPhoneNumber'] ?? "",
            'dateTime'  => date('Y-m-d H:i:s')
        ];
//FALTA TERMINAR EL SANEADOR Y CREAR EL VALIDADOR
        try {
            $userData = UserSanitizer::sanitize($userData);
            //UserValidator::validate($userData);

            $userController = new UserController();
            $userController->createUser($userData);
        } catch (Exception $e) {
            echo json_encode(['Catched error: ' . $e]);
            exit();
        }
    case 'loginUser':

        break;
    default:

        break;
}
