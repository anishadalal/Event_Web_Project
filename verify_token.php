<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "event_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = strtoupper(trim($_POST['token']));

    $sql = "SELECT b.*, e.title, e.date, e.location FROM bookings b 
            JOIN events e ON b.event_id = e.id 
            WHERE b.offline_token = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $booking = $result->fetch_assoc();
        echo "<div class='container'>";
        echo "<h2>Booking Verified!</h2>";
        echo "<p><strong>Event:</strong> {$booking['title']}</p>";
        echo "<p><strong>Date:</strong> {$booking['date']}</p>";
        echo "<p><strong>Location:</strong> {$booking['location']}</p>";
        echo "<p><strong>Name:</strong> {$booking['name']}</p>";
        echo "<p><strong>Email:</strong> {$booking['email']}</p>";
        echo "<p><strong>Seats:</strong> {$booking['seats']}</p>";
        echo "<p><strong>Amount Paid:</strong> ₹" . number_format($booking['amount'], 2) . "</p>";
        echo "<p><strong>Payment Method:</strong> " . ucfirst($booking['payment_method']) . "</p>";
        echo "</div>";
    } else {
        echo "<div class='container'><h2>Error</h2><p>Invalid Token. No booking found.</p></div>";
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Booking</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f9f9f9; color: #333; text-align: center; padding-top: 80px; }
        .navbar { background: white; padding: 10px; color: #111083; text-align: center; display: flex; align-items: center; justify-content: space-between; position: fixed; top: 0; left: 0; width: 100%; box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1); height: 80px; }
        .navbar img { width: 100px; height: 70px; margin: 15px; }
        .navbar h2 { margin: 0; flex: 1; text-align: center; color: #111083; }
        .container { max-width: 500px; margin: 50px auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1); }
        h2 { color: #111083; }
        .form-group { margin: 15px 0; }
        .form-group label { color: #111083; font-size: 20px; padding-bottom: 10px; display: block; }
        .form-group input { width: 90%; margin-top: 20px; padding: 10px; font-size: 16px; border: 1px solid #ccc; border-radius: 5px; text-transform: uppercase; }
        button { background: #111083; color: white; padding: 12px; border: none; border-radius: 5px; cursor: pointer; width: 23%; margin-top: 20px; }
        .back-button { background: #111083; color: white; padding: 12px; border: none; border-radius: 5px; cursor: pointer; width: 50px; margin-top: 5px; text-decoration: none; display: inline-block; text-align: center; font-size: 16px; transition: 0.3s; }
        .back-button:hover { background: #0b0b63; }
    </style>
</head>
<body>
    <div class="navbar">
        <img src="http://nextevent.co/wp-content/uploads/2019/02/next_event_logo_light.png" alt="Next Event Logo" class="imglogo">
        <h2>Verify Bookings</h2>
    </div>
    <div class="container">
        <form method="POST">
            <div class="form-group">
                <label for="token"><b>Enter Offline Token:</b></label>
                <input type="text" id="token" name="token" required>
            </div>
            <button type="submit">Check Booking</button>
        </form>
    </div>
    <a href="admin.php" class="back-button">Back</a>
</body>
</html>
