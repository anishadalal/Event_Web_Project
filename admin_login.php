<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "event_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Default admin credentials (Update this with the new password)
$default_username = "admin";
$default_password = "anidhanu"; // New password here

// Check if the default admin exists; if not, create it
$stmt = $conn->prepare("SELECT id, password FROM admins WHERE username = ?");
$stmt->bind_param("s", $default_username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // Hash the default password for storage
    $hashed_password = password_hash($default_password, PASSWORD_DEFAULT);

    // Insert the default admin into the database
    $insert_stmt = $conn->prepare("INSERT INTO admins (username, password) VALUES (?, ?)");
    $insert_stmt->bind_param("ss", $default_username, $hashed_password);
    $insert_stmt->execute();
    $insert_stmt->close();
} else {
    // If the admin exists, update the password
    $hashed_password = password_hash($default_password, PASSWORD_DEFAULT);
    $update_stmt = $conn->prepare("UPDATE admins SET password = ? WHERE username = ?");
    $update_stmt->bind_param("ss", $hashed_password, $default_username);
    $update_stmt->execute();
    $update_stmt->close();
}

$stmt->close();

// Handle login request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Prepare and execute the SQL query to fetch admin details
    $stmt = $conn->prepare("SELECT id, password FROM admins WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $admin = $result->fetch_assoc();

        // Verify the hashed password
        if (password_verify($password, $admin['password'])) {
            // Store the admin's ID in the session
            $_SESSION['admin_id'] = $admin['id'];

            // Redirect to the admin dashboard
            header("Location: admin.php");
            exit;
        } else {
            $error_message = "Invalid password.";
        }
    } else {
        $error_message = "Admin not found.";
    }

    $stmt->close();
} else {
    $error_message = "Invalid request method.";
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="login-container">
        <h2>Admin Login</h2>
        <?php if (!empty($error_message)): ?>
            <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>
        <form action="admin_login.php" method="POST">
            <input type="text" name="username" placeholder="Admin Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
