<?php
$servername = "localhost";
$username = "root";
$password = ""; // Replace with your actual MySQL password
$dbname = "event_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$success_message = "";
$error_message = "";
$edit_event = null;

// Fetch Event for Editing
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $result = $conn->query("SELECT * FROM events WHERE id = $id");
    if ($result->num_rows > 0) {
        $edit_event = $result->fetch_assoc();
    }
}

// Add or Update Event
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['event_id'] ?? null;
    $title = $_POST['title'];
    $description = $_POST['description'];
    $genre = $_POST['genre'];
    $location = $_POST['location'];
    $city = $_POST['city'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $price = $_POST['price'];
    $image = $_POST['image'];

    if ($id) {
        $sql = "UPDATE events SET title=?, description=?, genre=?, location=?, city=?, date=?, time=?, price=?, image=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssssisi", $title, $description, $genre, $location, $city, $date, $time, $price, $image, $id);
    } else {
        $sql = "INSERT INTO events (title, description, genre, location, city, date, time, price, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssssis", $title, $description, $genre, $location, $city, $date, $time, $price, $image);
    }

    if ($stmt->execute()) {
        $success_message = $id ? "Event updated successfully!" : "Event added successfully!";
        header("Location: " . $_SERVER['PHP_SELF']); // Redirect to clear form
        exit();
    } else {
        $error_message = "Error: " . $conn->error;
    }
    $stmt->close();
}

// Delete Event
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM events WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Fetch all events
$result = $conn->query("SELECT * FROM events");
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Events</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <style>
        .header {
            background-color: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 20px 30px;
            display: flex;
            align-items: center;
            justify-content: center; /* Center the title */
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
        }
        .header-title {
            font-size: 32px;
            font-weight: bold;
            color: #111083;
        }
        .imglogo {
            position: absolute;
            left: 20px; /* Keeps the logo on the left */
            width: 100px;
            height: 70px;
        }
        body {
            padding-top: 100px; /* Ensures the content is not hidden under the navbar */
        }
        .container {
            margin-top: 20px;
        }
        .form-group label {
            font-size: 16px; /* Increase label font size */
            font-weight: bold; /* Make labels bold */
        }

        /* Styling for the Verify button */
        .verify-button {
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

        /* Button hover effect */
        .verify-button:hover {
            background-color: #111083;
        }

        .nav-button {
    position: fixed;
    top: 20px;
    right: 20px;
    background-color: #111083;
    color: white;
    padding: 10px 20px;
    font-size: 16px;
    border-radius: 5px;
    text-decoration: none;
    z-index: 1100;
}

.nav-button:first-of-type {
    right: 100px; /* Moves 'Home' button to the left of 'Verify' button */
}

.nav-button:hover {
    background-color: #0d0c6b;
}

.nav-button {
    position: fixed;
    top: 20px;
    background-color: #111083;
    color: white;
    padding: 10px 20px;
    font-size: 16px;
    border-radius: 5px;
    text-decoration: none;
    z-index: 1100;
}

.nav-button:first-of-type {
    right: 110px; /* Increased space between Home and Verify button */
}

.nav-button:last-of-type {
    right: 20px;
}

.nav-button:hover {
    background-color: #0d0c6b;
}

    </style>
</head>
<body>
<div class="header">
    <img src="http://nextevent.co/wp-content/uploads/2019/02/next_event_logo_light.png" class="imglogo" alt="TheNextEvent Logo">
    <span class="header-title">Manage Events</span>
</div>

<!-- Navigation Buttons -->
<a href="index.php" class="nav-button" style="right: 160px;">Home</a>
<a href="search_event.php" class="nav-button" style="right: 90px;">Search</a>
<a href="verify_token.php" class="nav-button" style="right: 20px;">Verify</a>


<div class="container">
<h4 style="color: #111083; font-weight: bold;">Add Events</h4>

    <?php if ($success_message): ?>
        <p class="alert alert-success"><?= $success_message; ?></p>
    <?php elseif ($error_message): ?>
        <p class="alert alert-danger"><?= $error_message; ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <input type="hidden" name="event_id" value="<?= $edit_event['id'] ?? ''; ?>">
        <div class="form-group">
            <label>Event Title:</label>
            <input type="text" name="title" class="form-control" value="<?= $edit_event['title'] ?? ''; ?>" required>
        </div>
        <div class="form-group">
            <label>Description:</label>
            <textarea name="description" class="form-control" required><?= $edit_event['description'] ?? ''; ?></textarea>
        </div>
        <div class="form-group">
            <label>Genre:</label>
            <select name="genre" class="form-control" required>
                <?php
                $genres = ["Adventure", "Comedy Shows", "Exhibitions", "Festivals", "Food & Drinks", "Kids", "Music Shows", "Performances", "Tourists", "Workshops"];
                foreach ($genres as $genre) {
                    $selected = ($edit_event['genre'] ?? '') == $genre ? "selected" : "";
                    echo "<option value=\"$genre\" $selected>$genre</option>";
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label>Location:</label>
            <input type="text" name="location" class="form-control" value="<?= $edit_event['location'] ?? ''; ?>" required>
        </div>
        <div class="form-group">
            <label>City:</label>
            <select name="city" class="form-control" required>
                <?php
                $cities = ["Ahmedabad", "Bengaluru", "Chandigarh", "Chennai", "Delhi", "Hyderabad", "Kolkata", "Mumbai", "Pune"];
                foreach ($cities as $city) {
                    $selected = ($edit_event['city'] ?? '') == $city ? "selected" : "";
                    echo "<option value=\"$city\" $selected>$city</option>";
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label>Date:</label>
            <input type="date" name="date" class="form-control" value="<?= $edit_event['date'] ?? ''; ?>" required>
        </div>
        <div class="form-group">
            <label>Time:</label>
            <input type="time" name="time" class="form-control" value="<?= $edit_event['time'] ?? ''; ?>" required>
        </div>
        <div class="form-group">
            <label>Price:</label>
            <input type="number" name="price" class="form-control" value="<?= $edit_event['price'] ?? ''; ?>" required>
        </div>
        <div class="form-group">
            <label>Image URL:</label>
            <input type="text" name="image" class="form-control" value="<?= $edit_event['image'] ?? ''; ?>" required>
        </div>
        <button type="submit" class="btn btn-primary btn-block" style="background-color: #111083; border-color: #111083;">
    <?= $edit_event ? "Update Event" : "Save Event"; ?>
</button>

    </form>

    <table class="table table-bordered mt-4">
        <tr>
            <th>Title</th><th>Genre</th><th>City</th><th>Date</th><th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['title']; ?></td>
                <td><?= $row['genre']; ?></td>
                <td><?= $row['city']; ?></td>
                <td><?= $row['date']; ?></td>
                <td>
                    <a href="?edit=<?= $row['id']; ?>" class="btn btn-sm" style="background-color: #739BD0; color: white;">Edit</a>
                    <a href="?delete=<?= $row['id']; ?>" class="btn btn-sm" style="background-color: #0047AB; color: white;" onclick="return confirm('Are you sure?')">Delete</a>
                </td>

            </tr>
        <?php endwhile; ?>
    </table>
</div>
</body>
</html>