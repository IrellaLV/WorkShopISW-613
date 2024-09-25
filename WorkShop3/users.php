<?php
require('utils/functions.php');

$conn = getConnection();
$sql = "SELECT u.firstName, u.lastName, u.email, p.name AS province 
        FROM users u 
        JOIN provincias p ON u.province_id = p.id";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>All Users</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>
  <div class="container">
    <h1>List of Users</h1>
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Name</th>
          <th>Last Name</th>
          <th>Email</th>
          <th>Province</th>
        </tr>
      </thead>
      <tbody>
        <?php while($user = mysqli_fetch_assoc($result)): ?>
          <tr>
            <td><?php echo $user['firstName']; ?></td>
            <td><?php echo $user['lastName']; ?></td>
            <td><?php echo $user['email']; ?></td>
            <td><?php echo $user['province']; ?></td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
    <!-- Button to go back to index.php -->
    <a href="index.php" class="btn btn-primary">Back to Home</a>
  </div>
</body>
</html>
