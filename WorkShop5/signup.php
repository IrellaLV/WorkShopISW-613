<?php
include('utils/functions.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recoger los datos del formulario
    $user = [
        'firstName' => $_POST['firstName'],
        'lastName' => $_POST['lastName'],
        'email' => $_POST['email'],
        'province_id' => $_POST['province'],
        'password' => $_POST['password'],
        'status' => 'active' // Asignar el estado por defecto como "active"
    ];

    // Guardar el usuario en la base de datos
    $isSaved = saveUser($user);

    if ($isSaved) {
        // Redirigir al usuario a la página de inicio de sesión
        header('Location: loginIndex.php');
        exit();
    } else {
        echo "Error al crear el usuario. Inténtalo de nuevo.";
    }
}

