<?php
function getConnection() {
    $connection = mysqli_connect('localhost', 'root', '', 'signup');
    return $connection;
}

if ($argc < 2) {
    echo "Usage: php validateActiveSessions.php <hours>\n";
    exit(1);
}

$hours = intval($argv[1]);
$timeLimit = date('Y-m-d H:i:s', strtotime("-$hours hours"));
$conn = getConnection();

$sql = "UPDATE users SET status = 'inactive' WHERE status = 'active' AND last_login_datetime < ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 's', $timeLimit);
$result = mysqli_stmt_execute($stmt);

if ($result) {
    echo "Updated users to inactive who haven't logged in since $timeLimit.\n";
} else {
    echo "Error updating users: " . mysqli_error($conn) . "\n";
}

mysqli_close($conn);
?>