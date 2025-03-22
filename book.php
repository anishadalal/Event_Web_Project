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

$eventId = isset($_GET['event']) && is_numeric($_GET['event']) ? intval($_GET['event']) : null;

if ($eventId) {
    $sql = "SELECT * FROM events WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $eventId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $event = $result->fetch_assoc();
    } else {
        die('<h2>Event not found.</h2><p>Please select a valid event.</p>');
    }
} else {
    die('<h2>No event selected.</h2><p>Please select an event.</p>');
}

$_SESSION['event'] = $event;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars(trim($_POST['name']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $seats = isset($_POST['seats']) && is_numeric($_POST['seats']) ? intval($_POST['seats']) : 1;

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<div class='container'><h2>Error</h2><p>Invalid email format.</p></div>";
        exit;
    }

    $totalAmount = $seats * $event['price'];
    $offlineToken = strtoupper(substr(md5(time() . $name), 0, 8));

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = htmlspecialchars(trim($_POST['name']));
        $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
        $seats = isset($_POST['seats']) && is_numeric($_POST['seats']) ? intval($_POST['seats']) : 1;
    
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "<div class='container'><h2>Error</h2><p>Invalid email format.</p></div>";
            exit;
        }
    
        $totalAmount = $seats * $event['price'];
        $offlineToken = strtoupper(substr(md5(time() . $name), 0, 8));
    
        // Save the booking in the database
        $stmt = $conn->prepare("INSERT INTO bookings (event_id, name, email, seats, amount, payment_method, offline_token) VALUES (?, ?, ?, ?, ?, 'offline', ?)");
        $stmt->bind_param("issids", $eventId, $name, $email, $seats, $totalAmount, $offlineToken);
        $stmt->execute();
        $stmt->close();
        $conn->close();
    
        // Display confirmation page
        echo "<!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Booking Confirmation</title>
            <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css'>
            <style>
                body { font-family: Arial, sans-serif; background: #f9f9f9; color: #333; }
                .header {
                    background: #ffffff;
                    padding: 15px;
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    border-bottom: 2px solid #ddd;
                }
                .header img {
                    width: 100px;
                    height: 70px;
                }
                .header h1 {
                    flex-grow: 1;
                    text-align: center;
                    font-size: 28px;
                    font-weight: bold;
                    color: #111083;
                    margin: 0;
                }
                .navbar {
                    background: #111083;
                    padding: 10px;
                    display: flex;
                    justify-content: center;
                }
                .navbar a {
                    color: white;
                    text-decoration: none;
                    padding: 10px 20px;
                    font-size: 18px;
                }
                .navbar a:hover {
                    background: #3336a2;
                    border-radius: 5px;
                }
                .container {
                max-width: 600px;
                margin: 50px auto;
                background: white;
                padding: 30px;
                border-radius: 10px;
                box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
                text-align: center;
                display: flex;
                flex-direction: column;
                justify-content: center; /* Centers vertically */
                align-items: center; /* Centers horizontally */
                min-height: 300px; /* Adjust height as needed */
                }

                h3 { color:#000000 }
                
            </style>
        </head>
        <body>
            <div class='header'>
                <img src='http://nextevent.co/wp-content/uploads/2019/02/next_event_logo_light.png' alt='Logo'>
                <h1>Booking Confirmation</h1>
            </div>
            
            <div class='container'>
                <h3>Booking Successful!</h3>
                <p>Thank you, <strong>$name</strong>, for booking tickets for <strong>{$event['title']}</strong>.</p>
                <p>A confirmation email has been sent to <strong>$email</strong>.</p>
                <p><strong>Total Amount: ₹" . number_format($totalAmount, 2) . "</strong></p>
                <p><strong>Show this offline token at the venue to pay:</strong></p>
                <p style='color: red; font-size: 24px; font-weight: bold;'>$offlineToken</p>
            </div>

            <a href='index.php' style='display: block; text-align: center; width: fit-content; margin: 20px auto; background: #111083; color: white; padding: 8px 16px; font-size: 14px; border-radius: 5px; text-decoration: none;'>Back</a>


        </body>
        </html>";
        exit;
    }
    
    echo "</div>";

    $stmt = $conn->prepare("INSERT INTO bookings (event_id, name, email, seats, amount, payment_method, offline_token) VALUES (?, ?, ?, ?, ?, 'offline', ?)");
    $stmt->bind_param("issids", $eventId, $name, $email, $seats, $totalAmount, $offlineToken);
    $stmt->execute();
    $stmt->close();
    exit;
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TheNextEvent - Book Tickets</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <script>
        function updateTotal() {
            let seats = document.getElementById('seats').value;
            let price = <?= $event['price']; ?>;
            let total = seats * price;
            document.getElementById('totalAmount').innerText = '₹' + total;
        }
    </script>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            background: #f9f9f9; 
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
        .header img {
            width: 100px;
            height: 70px;
        }
        .header h1 {
            flex-grow: 1;
            text-align: center;
            font-size: 32px;
            font-weight: bold;
            color: #111083;
            margin: 0;
        }
        .container { 
            max-width: 600px; 
            margin: 0 auto; 
            background: white; 
            padding: 20px; 
            border-radius: 10px; 
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1); 
        }
        h2 { 
            color: #111083; 
        }
        .form-group { 
            margin: 15px 0; 
        }
        .form-group input { 
            width: 100%; 
            padding: 10px; 
            font-size: 16px; 
            border: 1px solid #ccc; 
            border-radius: 5px; 
        }
        button { 
            background: #111083; 
            color: white; 
            padding: 12px; 
            border: none; 
            border-radius: 5px; 
            cursor: pointer; 
            width: 100%; 
            margin-top: 20px; 
        }
        button:hover {
            background: #3336a2;
        }
        .event-details p { 
            font-size: 16px; 
        }
        .event-details {
            background: #f0f0f0;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .back-button {
            background: #ccc;
            color: #333;
            padding: 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            margin-top: 10px;
            text-align: center;
            text-decoration: none;
        }
        .back-button:hover {
            background: #aaa;
        }

        .back-button {
    background: #ccc;
    color: #333;
    padding: 12px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    width: 100%;
    margin-top: 10px;
    text-align: center;
    text-decoration: none;
    transition: all 0.3s ease-in-out;
    display: inline-block;
    font-weight: bold;
}

.back-button:hover {
    background: #aaa;
    transform: scale(1.05);
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
}

    </style>
</head>
<body>
    <div class="header">
        <img src="http://nextevent.co/wp-content/uploads/2019/02/next_event_logo_light.png" alt="Logo">
        <h1>Book Your Tickets</h1>
    </div>
    <div class="container">
        <div class="event-details">
            <h4><b><?= htmlspecialchars($event['title']); ?></b></h4>
            <p><strong>Date:</strong> <?= htmlspecialchars($event['date']); ?></p>
            <p><strong>Location:</strong> <?= htmlspecialchars($event['location']); ?></p>
            <p><strong>Price:</strong> ₹<?= htmlspecialchars($event['price']); ?> per seat</p>
        </div>
        <form method="POST">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="seats">Number of Seats:</label>
                <input type="number" id="seats" name="seats" required min="1" value="1" oninput="updateTotal()">
            </div>
            <p><strong>Total Amount: <span id="totalAmount">₹<?= $event['price']; ?></span></strong></p>
            <button type="submit">Generate Offline Token</button>
        </form>
    </div>
</body>
</html>