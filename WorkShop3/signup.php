<?php
require('utils/functions.php');

if($_POST && $_REQUEST['firstName']) {
  // Collect form data
  $user['firstName'] = $_REQUEST['firstName'];
  $user['lastName'] = $_REQUEST['lastName'];
  $user['email'] = $_REQUEST['email'];
  $user['province_id'] = $_REQUEST['province']; // Capture province_id
  $user['password'] = $_REQUEST['password'];
  
  if (saveUser($user)) {
    // Redirigir a la misma página para reiniciar el formulario
    header("Location: index.php"); 
    exit;
  } else {
    // Redirigir con un parámetro de error
    header("Location: " . $_SERVER['PHP_SELF'] . "?error=true");
    exit;
  }
}
?>
