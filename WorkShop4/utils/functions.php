<?php
function getConnection() {
  $connection = mysqli_connect('localhost', 'root', '', 'signup');
  return $connection;
}

function getProvinces() {
  $conn = getConnection();
  $sql = "SELECT id, name FROM provincias";
  $result = mysqli_query($conn, $sql);
  
  $provinces = [];
  while($row = mysqli_fetch_assoc($result)) {
    $provinces[$row['id']] = $row['name'];
  }
  return $provinces;
}

function getUserById($userId) {
  $conn = getConnection();
  $sql = "SELECT * FROM users WHERE id = ?";
  $stmt = mysqli_prepare($conn, $sql);
  mysqli_stmt_bind_param($stmt, 'i', $userId);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  return mysqli_fetch_assoc($result);
}

function saveUser($user) {
  $conn = getConnection();
  $sql = "INSERT INTO users (firstName, lastName, email, province_id, password) 
          VALUES (?, ?, ?, ?, ?)";
  $stmt = mysqli_prepare($conn, $sql);
  $password = password_hash($user['password'], PASSWORD_DEFAULT); 
  mysqli_stmt_bind_param($stmt, 'sssis', $user['firstName'], $user['lastName'], $user['email'], $user['province_id'], $password);
  return mysqli_stmt_execute($stmt);
}

function updateUser($user) {
  $conn = getConnection();
  $sql = "UPDATE users SET firstName = ?, lastName = ?, email = ?, province_id = ? WHERE id = ?";
  $stmt = mysqli_prepare($conn, $sql);
  mysqli_stmt_bind_param($stmt, 'sssii', $user['firstName'], $user['lastName'], $user['email'], $user['province_id'], $user['id']);
  return mysqli_stmt_execute($stmt);
}

function deleteUser($userId) {
  $conn = getConnection();
  $sql = "DELETE FROM users WHERE id = ?";
  $stmt = mysqli_prepare($conn, $sql);
  mysqli_stmt_bind_param($stmt, 'i', $userId);
  return mysqli_stmt_execute($stmt);
}

function authenticateUser($identifier, $password) {
  $conn = getConnection();

  // Verificar si el identifier es un correo o nombre de usuario
  $sql = "SELECT * FROM users WHERE email = ? OR firstName = ?";
  $stmt = mysqli_prepare($conn, $sql);
  mysqli_stmt_bind_param($stmt, 'ss', $identifier, $identifier);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  
  if ($row = mysqli_fetch_assoc($result)) {
      // Verificar la contraseña
      if (password_verify($password, $row['password'])) {
          return true; // Login exitoso
      } else {
          return false; // Contraseña incorrecta
      }
  } else {
      return false; // Usuario no encontrado
  }
}
