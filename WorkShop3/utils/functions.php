<?php
function getProvinces() {
    $conn = getConnection();
    $sql = "SELECT name FROM provincias";
    $result = mysqli_query($conn, $sql);
    
    $provinces = [];
    $index = 1;
    while($row = mysqli_fetch_assoc($result)) {
      $provinces[$index] = $row['name'];
      $index++;
    }
    
    return $provinces;
  }
  

function getConnection() {
  $connection = mysqli_connect('localhost', 'root', '', 'signup');
  return $connection;
}

/**
 * Saves an specific user into the database
 */
function saveUser($user) {
  $firstName = $user['firstName'];
  $lastName = $user['lastName'];
  $email = $user['email'];
  $province_id = $user['province_id']; // Note the change to province_id
  $password = password_hash($user['password'], PASSWORD_DEFAULT);

  $sql = "INSERT INTO users (firstName, lastName, email, province_id, password) 
          VALUES ('$firstName', '$lastName', '$email', '$province_id', '$password')";

  $conn = getConnection();
  mysqli_query($conn, $sql);

  return true;
}

  
  