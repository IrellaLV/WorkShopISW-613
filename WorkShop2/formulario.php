<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "usuarios";


$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $telefono = $_POST['telefono'];
    $correo = $_POST['correo'];

    $sql = "INSERT INTO usuarios (Nombre, Apellido, Telefono, Correo) VALUES (?, ?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssss", $nombre, $apellido, $telefono, $correo);

        if ($stmt->execute()) {
            echo "
                <div style='color: green; font-size: 20px; text-align: center;'>
                    <span style='font-size: 40px;'>&#10003;</span> Datos insertados correctamente.
                </div>
            ";
        } else {
            echo "Error al insertar los datos: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error en la preparación de la consulta: " . $conn->error;
    }
    $conn->close();
} else {
    echo "Error: No se ha enviado el formulario.";
}
?>
