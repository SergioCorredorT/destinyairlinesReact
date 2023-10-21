<?php

//Login, editar usuario, eliminar cuenta
require_once './Controllers/BaseController.php';
class UserController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getUser($email, $password)
    {
        $UserModel = new UserModel();
        $results = $UserModel->readUserByEmail($email, $password);
        print_r($results);
    }

    public function createUser($data)
    {
        $UserModel = new UserModel();
        if ($UserModel->createUser($data)) {
            echo "Usuario creado con exito";
        } else {
            throw new Exception("Failed to create user");
        }
    }

    public function deleteUser($email, $password)
    {
        $UserModel = new UserModel();
        if ($UserModel->deleteUserByEmailAndPassword($email, $password)) {
            echo "Cuenta borrada exitosamente";
        } else {
            throw new Exception("Failed to delete user");
        }
    }
}
