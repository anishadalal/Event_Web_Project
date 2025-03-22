<?php
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

// Check if event ID is passed via URL, and sanitize it
if (isset($_GET['event']) && is_numeric($_GET['event'])) {
    $eventId = intval($_GET['event']);
} else {
    // Redirect to homepage if no valid event ID is passed
    header("Location: index.php");
    exit();
}

// Fetch event details from the database
$sql = "SELECT * FROM events WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $eventId);
$stmt->execute();
$result = $stmt->get_result();

// Check if the event exists
if ($result->num_rows === 0) {
    // Event not found, redirect to homepage
    header("Location: index.php");
    exit();
}

// Fetch the event data
$event = $result->fetch_assoc();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Details - TheNextEvent</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(to bottom, #f0f0f0, #ffffff);
            color: #333;
        }
        .header {
            background: #ffffff;
            padding: 20px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: none;
        }
        .header-content {
            display: flex;
            flex: 1;
            justify-content: center;
            align-items: center;
        }
        .imglogo {
            width: 100px;
            height: 70px;
            margin-right: 20px;
        }
        h2 {
            color: #111083;
            font-size: 36px; /* Increase the font size */
            font-weight: bold; /* Make the heading bold */
            margin: 0;
            text-align: center;
        }
        .login-btn3 {
            background: #111083;
            color: white;
            padding: 12px 30px;
            border-radius: 30px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s ease;
            margin-left: auto;
        }
        .login-btn:hover {
            background: #111083;
            transform: scale(1.05);
        } 
        .container {
            margin: 50px auto;
            border-radius: 10px;
            text-align: center;
            height: 400px;
        }
        .event-info {
            margin: 20px 0;
        }
        .event-info p {
            font-size: 16px;
            text-align: left;
        }
        .event-image {
            width: 45%;
            height: 350px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .btn {
            padding: 12px 20px;
            background: #111083;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            margin-right: 400px;
        }
        .btn:hover {
            background: #111083;
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <div class="col-12 header">
            <img src="http://nextevent.co/wp-content/uploads/2019/02/next_event_logo_light.png" class="imglogo" alt="Logo">
            <div class="header-content">
                <h2><?= htmlspecialchars($event['title']); ?></h2>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-6">
            <img src="<?= htmlspecialchars($event['image']); ?>" alt="<?= htmlspecialchars($event['title']); ?>" class="event-image">
        </div>
        <div class="col-6">
            <div class="event-info">
                <p><strong>Date:</strong> <?= htmlspecialchars($event['date']); ?></p>
                <p><strong>Time:</strong> <?= htmlspecialchars($event['time']); ?></p>
                <p><strong>Location:</strong> <?= htmlspecialchars($event['location']); ?></p>
                <p><strong>City:</strong> <?= htmlspecialchars($event['city']); ?></p>
                <p><strong>Genre:</strong> <?= htmlspecialchars($event['genre']); ?></p>
                <p><strong>Price:</strong> <?= number_format($event['price'], 2); ?></p>
                <p><strong>Description:</strong> <?= nl2br(htmlspecialchars($event['description'])); ?></p>
            </div>
            <a href="book.php?event=<?= $eventId; ?>" class="btn">Book Tickets</a>
        </div>
    </div>
</div>
</body>
</html>
