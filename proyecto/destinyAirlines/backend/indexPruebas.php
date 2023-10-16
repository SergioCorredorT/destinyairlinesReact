<?php
include 'Models/UserModel.php';
$usuario = new UserModel();
print_r($usuario->readUsers());
