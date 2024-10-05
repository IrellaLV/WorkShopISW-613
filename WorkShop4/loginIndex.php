<?php
include('utils/functions.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $identifier = $_POST['identifier']; 
    $password = $_POST['password'];

    if (authenticateUser($identifier, $password)) {
        // Iniciar sesión y redirigir a la página principal
        $_SESSION['user'] = $identifier; // Guarda al usuario en la sesión
        header('Location: users.php');
        exit();
    } else {
        $error = "Correo o nombre y/o contraseña incorrectos.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" crossorigin="anonymous">
</head>
<body>
    <div class="container-fluid">
        <div class="jumbotron">
            <h1 class="display-4">Login</h1>
            <p class="lead">Introduce tu correo o nombre y contraseña para iniciar sesión.</p>
            <hr class="my-4">
        </div>
        <form method="post" action="loginIndex.php">
            <div class="form-group">
                <label for="identifier">Correo o Nombre</label>
                <input id="identifier" class="form-control" type="text" name="identifier" required>
            </div>
            <div class="form-group">
                <label for="password">Contraseña</label>
                <input id="password" class="form-control" type="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
            <a href="index.php" class="btn btn-primary">Sign up</a>
        </form>
        <br>
        <?php if(isset($error)): ?>
        <div class="alert alert-danger">
            <?php echo $error; ?>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>