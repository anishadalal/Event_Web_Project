<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Bookings</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; margin: 0; padding: 0; }
        .navbar {
            background-color: white;
            padding: 10px;
            text-align: center;
            display: flex;
            justify-content: space-between;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            height: 80px;
            align-items: center;
        }
        .navbar img {
            width: 100px;
            height: 70px;
            margin: 15px;
        }
        .navbar h2 {
            margin: 0;
            flex: 1;
            text-align: center;
            color: #111083;
        }
        .back-button {
            position: fixed;
    top: 20px;
    right: 20px;
    z-index: 1100;
    background-color: #111083;
    color: white;
    padding: 10px 20px;
    font-size: 16px;
    border-radius: 5px;
    text-decoration: none;
        }
        .back-button:hover {
            background-color: #111083;
        }
        .container { margin: 120px auto 20px; width: 50%; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid black; }
        th, td { padding: 10px; text-align: left; }
        th:nth-child(4), td:nth-child(4) {  
            width: 200px; /* Increased width for Booking Time column */  
            word-wrap: break-word;  
        }
        form { margin-bottom: 20px; }
        input[type="number"] {
            padding: 10px;
            font-size: 18px;
            width: 250px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        input[type="submit"] {
            background-color: #111083;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 18px;
            cursor: pointer;
            border-radius: 5px;
        }
        input[type="submit"]:hover {
            background-color: #0e0e72;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <img src="http://nextevent.co/wp-content/uploads/2019/02/next_event_logo_light.png" alt="Next Event Logo" class="imglogo">
        <h2>Search Event Bookings</h2>
        <a href="admin.php" class="back-button">Back</a>
    </div>
    
    <div class="container">
        
        <form method="post">
            <label for="event_id">Enter Event ID:</label>
            <input type="number" name="event_id" required>
            <input type="submit" value="Search">
        </form>

        <?php
        // Database connection
        $host = "localhost";
        $user = "root";
        $password = "";
        $database = "event_db";

        $conn = new mysqli($host, $user, $password, $database);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        if (isset($_POST['event_id'])) {
            $event_id = $_POST['event_id'];
            $sql = "SELECT name, email, payment_status, created_at, seats, amount FROM bookings WHERE event_id = ?";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                die("SQL Error: " . $conn->error);
            }
            $stmt->bind_param("i", $event_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                echo "<h3>Bookings for Event ID: $event_id</h3>";
                echo "<p>Total Bookings: " . $result->num_rows . "</p>";
                echo "<table>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Payment Status</th>
                            <th>Booking Time</th>
                            <th>Seats</th>
                            <th>Amount</th>
                        </tr>";
                
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['name']}</td>
                            <td>{$row['email']}</td>
                            <td>{$row['payment_status']}</td>
                            <td>{$row['created_at']}</td>
                            <td>{$row['seats']}</td>
                            <td>{$row['amount']}</td>
                          </tr>";
                }
                echo "</table>";
            } else {
                echo "<p>No bookings found for this event.</p>";
            }
            $stmt->close();
        }
        $conn->close();
        ?>
    </div>
</body>
</html>
