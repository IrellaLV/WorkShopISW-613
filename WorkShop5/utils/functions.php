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
  $sql = "INSERT INTO users (firstName, lastName, email, province_id, password, status) 
          VALUES (?, ?, ?, ?, ?, ?)";
  $stmt = mysqli_prepare($conn, $sql);
  $password = password_hash($user['password'], PASSWORD_DEFAULT); 
  mysqli_stmt_bind_param($stmt, 'sssisi', $user['firstName'], $user['lastName'], $user['email'], $user['province_id'], $password, $user['status']);
  return mysqli_stmt_execute($stmt);
}

function updateUser($user) {
  $conn = getConnection();
  $sql = "UPDATE users SET firstName = ?, lastName = ?, email = ?, province_id = ?, status = ? WHERE id = ?";
  $stmt = mysqli_prepare($conn, $sql);
  mysqli_stmt_bind_param($stmt, 'sssisi', $user['firstName'], $user['lastName'], $user['email'], $user['province_id'], $user['status'], $user['id']);
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
  // Consulta para buscar el usuario
  $sql = "SELECT * FROM users WHERE email = ? OR firstName = ? LIMIT 1";
  $stmt = mysqli_prepare($conn, $sql);
  mysqli_stmt_bind_param($stmt, 'ss', $identifier, $identifier);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  
  if ($user = mysqli_fetch_assoc($result)) {
      if ($user['status'] === 'inactive') {
          return 'inactive';
      }
      // Compara la contraseña (puedes usar password_verify si usas password_hash)
      if (password_verify($password, $user['password'])) {
          // Actualiza la fecha y hora del último inicio de sesión
          updateLastLogin($user['id']); // Aquí se llama a la función
          return 'success';
      } else {
          return 'wrong_password';
      }
  }
  return 'not_found';
}

function updateLastLogin($userId) {
  $conn = getConnection();
  $sql = "UPDATE users SET last_login_datetime = NOW() WHERE id = ?";
  $stmt = mysqli_prepare($conn, $sql);
  mysqli_stmt_bind_param($stmt, 'i', $userId);
  return mysqli_stmt_execute($stmt);
}