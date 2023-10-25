<?php
//-----------------------------------------------------------------------------------------------------------------------
//    include 'Models/UserModel.php';
//    $usuario = new UserModel();
/*
        $datas = [
            ['firstName' => 'AAAA', 'zipCode' => 25, 'emailAddress' => 'aaaa@example.com', 'password' => 'contraseña1'],
            ['firstName' => 'BBBB', 'zipCode' => 30, 'emailAddress' => 'bbbb@example.com', 'password' => 'contraseña2'],
            ['firstName' => 'CCCC', 'zipCode' => 28, 'emailAddress' => 'cccc@example.com', 'password' => 'contraseña3']
        ];
        if($usuario->createUsers($datas)){echo "bieeeeeeeeeeen";}else{echo "maaaaaaaaaaaal";};
*/

//print_r($usuario->readUsers());

//$datas = ["zipCode"=>"11113", "phoneNumber3"=>"123456"];
//if($usuario->updateUsers($datas, "country LIKE 'USA'")){echo "bieeeeeeeeeeen";}else{echo "maaaaaaaaaaaal";};

//if($usuario->deleteUsers("firstName LIKE AAAA")){echo "bieeeeeeeeeeen";}else{echo "maaaaaaaaaaaal";};

/*
    $data = [
        'firstName' => 'A555', 'zipCode' => 25, 'emailAddress' => 'aaaa5@example.com', 'password' => 'contraseña5'
    ];
    if($usuario->createUsers($data)){echo "bieeeeeeeeeeen";}else{echo "maaaaaaaaaaaal";};
*/
//-----------------------------------------------------------------------------------------------------------------------


function prueba($data)
{
$url = 'http://localhost/destinyairlinesReact/proyecto/destinyAirlines/backend/MainController.php'; // Cambia esto a la URL de tu MainController
// Inicializar cURL
$ch = curl_init($url);

// Configurar las opciones de cURL
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Ejecutar la solicitud POST
$response = curl_exec($ch);

// Cerrar la sesión cURL
curl_close($ch);

// Imprimir la respuesta
if ($response) {
    echo "bieeeeeeeen\n";
} else {
    echo "maaaaaaaaaaaaal\n";
}

return $response;
}

/*
$data = [
    'command'    => 'contact',
    'name'      => "Sergio",
    'email'     => "waa@gmail.com",
    'phoneNumber' => "111223344",
    'subject'   => "motivazo bueno",
    'message'   => "Mensaje guauuuuuuuuuuuuuuu"
];
*/
/*
$data = [
    'command'    => 'createuser',
    'firstName' => 'A555', 
    'zipCode' => 25, 
    'emailAddress' => 'aaaa5@example.com', 
    'password' => 'contraseña5'
];

/*
$data = [
    'command'    => 'removeuser',
    'emailAddress' => 'aaaa5@example.com', 
    'password' => 'contraseña5'
];
*/

/*
$data = [
    'command'    => 'loginUser',
    'emailAddress' => 'aaaa5@example.com', 
    'password' => 'contraseña5'
];
*/
/*
$response=prueba($data);
print_r("Prueba1:".$response);
*/
//$response=json_decode($response);

$data2 = [
    'command'    => 'logoutUser',
    'token'     => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImtpZCI6IjU5NzhiNWUwLWZlYmQtNGFhZC1iZmM5LWY3NDYwOSJ9.eyJpc3MiOiJkZXN0aW55QWlybGluZXMiLCJhdWQiOiJkZXN0aW55QWlybGluZXMiLCJpYXQiOjE2OTgyNTE3NDUsImV4cCI6MTY5ODI1NTM0NSwiZGF0YSI6eyJpZCI6MTEzLCJlbWFpbCI6ImFhYWE1QGV4YW1wbGUuY29tIn0sInJvbGUiOiJ1c2VyIn0.4jSqcOwA0JWDsk_TqCGQOH0IK6BdlHHzE42hrBMLboA'
];
print_r("Prueba2:".prueba($data2));
