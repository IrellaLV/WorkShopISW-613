<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: loginIndex.php');
    exit();
}
require('utils/functions.php');

$conn = getConnection();
$sql = "SELECT u.id, u.firstName, u.lastName, u.email, p.name AS province 
        FROM users u 
        JOIN provincias p ON u.province_id = p.id";
$result = mysqli_query($conn, $sql);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = isset($_POST['userId']) ? $_POST['userId'] : null; // Evitar error por undefined index
    $user = [
        'firstName' => isset($_POST['firstName']) ? $_POST['firstName'] : null,
        'lastName' => isset($_POST['lastName']) ? $_POST['lastName'] : null,
        'email' => isset($_POST['email']) ? $_POST['email'] : null,
        'province_id' => isset($_POST['province_id']) ? $_POST['province_id'] : null
    ];

    if ($userId) {
        // Update user
        $user['id'] = $userId;
        updateUser($user);
    } else {
        // Create new user
        $user['password'] = 'password';
        saveUser($user);
    }

    header("Location: users.php"); // Refresca la página después de la inserción o actualización
    exit(); // Importante para detener la ejecución del script después de redireccionar
}

if (isset($_GET['delete'])) {
    $userId = $_GET['delete'];
    deleteUser($userId);
    header("Location: users.php");
    exit();
}

// Handle edit
if (isset($_GET['edit'])) {
    $userId = $_GET['edit'];
    $editUser = getUserById($userId);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Management</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>
  <div class="container">
    <h1>Manage Users</h1>
    
    <form action="users.php" method="POST" class="mb-4">
      <div class="form-row">
        <div class="col">
          <input type="hidden" name="userId" value="<?php echo isset($editUser) ? $editUser['id'] : ''; ?>">
          <input type="text" class="form-control" name="firstName" placeholder="First Name" value="<?php echo isset($editUser) ? $editUser['firstName'] : ''; ?>" required>
        </div>
        <div class="col">
          <input type="text" class="form-control" name="lastName" placeholder="Last Name" value="<?php echo isset($editUser) ? $editUser['lastName'] : ''; ?>" required>
        </div>
        <div class="col">
          <input type="email" class="form-control" name="email" placeholder="Email" value="<?php echo isset($editUser) ? $editUser['email'] : ''; ?>" required>
        </div>
        <div class="col">
          <select class="form-control" name="province_id" required>
            <option value="">Select Province</option>
            <?php
            $provinces = getProvinces();
            foreach ($provinces as $id => $province) {
                $selected = (isset($editUser) && $editUser['province_id'] == $id) ? 'selected' : '';
                echo "<option value='$id' $selected>" . $province . "</option>";
            }
            ?>
          </select>
        </div>
        <div class="col">
          <button type="submit" class="btn btn-success"><?php echo isset($editUser) ? 'Update' : 'Add'; ?></button>
        </div>
      </div>
    </form>

    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Name</th>
          <th>Last Name</th>
          <th>Email</th>
          <th>Province</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php while($user = mysqli_fetch_assoc($result)): ?>
          <tr>
            <td><?php echo $user['firstName']; ?></td>
            <td><?php echo $user['lastName']; ?></td>
            <td><?php echo $user['email']; ?></td>
            <td><?php echo $user['province']; ?></td>
            <td>
              <a href="users.php?edit=<?php echo $user['id']; ?>" class="btn btn-primary">Edit</a>
              <a href="users.php?delete=<?php echo $user['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
    <a href="loginIndex.php" class="btn btn-primary">Back to Home</a>
  </div>
</body>
</html>