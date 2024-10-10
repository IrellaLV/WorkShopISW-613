<?php
include('utils/functions.php');
session_start();
$message = ''; // Variable para almacenar mensajes

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Aquí va tu lógica de autenticación
    $identifier = $_POST['identifier'];
    $password = $_POST['password'];
    
    $authResult = authenticateUser($identifier, $password);
    
    if ($authResult === 'success') {
        $_SESSION['user'] = $identifier; // Guardar el identificador del usuario en la sesión
        
        // Obtener el ID del usuario para actualizar el último inicio de sesión
        
        updateLastLogin($userId); // Actualizar el último inicio de sesión
        
        header('Location: users.php'); // Redirigir a la página de inicio
        exit();
    } elseif ($authResult === 'inactive') {
        $message = 'Your account is inactive. Please contact support.'; // Mensaje para usuario inactivo
    } elseif ($authResult === 'wrong_password') {
        $message = 'Incorrect password. Please try again.'; // Mensaje para contraseña incorrecta
    } elseif ($authResult === 'not_found') {
        $message = 'User not found. Please check your credentials.'; // Mensaje para usuario no encontrado
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Login</h1>

        <!-- Mensaje de error -->
        <?php if ($message): ?>
            <div class="alert alert-danger"><?php echo $message; ?></div>
        <?php endif; ?>

        <!-- Formulario de inicio de sesión -->
        <form action="loginIndex.php" method="POST">
            <div class="form-group">
                <label for="identifier">Email or Username</label>
                <input type="text" class="form-control" name="identifier" placeholder="Enter email or username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" name="password" placeholder="Enter password" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
            <a href="index.php" class="btn btn-primary">Signup</a>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>