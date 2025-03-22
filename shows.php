<?php
$servername = "localhost";
$username = "root";
$password = ""; // Replace with your actual MySQL password
$dbname = "event_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetching filter values from GET request
$filterPrice = isset($_GET['price']) ? $_GET['price'] : '';
$filterGenre = isset($_GET['genre']) ? $_GET['genre'] : '';
$filterDate = isset($_GET['date']) ? $_GET['date'] : '';

// Base query
if(isset($_GET['City']) && ($_GET['City']!=''))
{
   
    $query = "SELECT * FROM events WHERE 1=1 AND city='".$_GET['City']."'";
}
else{
    
    $query = "SELECT * FROM events WHERE 1=1 ";
}

// Dynamic filters
$params = [];
$types = "";

if ($filterPrice) {
    if ($filterPrice == 'free') {
        $query .= " AND price = ?";
        $params[] = 0;
        $types .= "i";
    } elseif ($filterPrice == 'below_200') {
        $query .= " AND price < ?";
        $params[] = 200;
        $types .= "i";
    } elseif ($filterPrice == '200_500') {
        $query .= " AND price BETWEEN ? AND ?";
        $params[] = 200;
        $params[] = 500;
        $types .= "ii";
    } elseif ($filterPrice == 'above_500') {
        $query .= " AND price > ?";
        $params[] = 500;
        $types .= "i";
    }
}

if ($filterGenre) {
    $query .= " AND genre = ?";
    $params[] = $filterGenre;
    $types .= "s";
}

if ($filterDate) {
    $query .= " AND date = ?";
    $params[] = $filterDate;
    $types .= "s";
}

// Prepare and execute query
$stmt = $conn->prepare($query);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();
$filteredEvents = $result->fetch_all(MYSQLI_ASSOC);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TheNextEvent - Shows</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to bottom, #f0f0f0, #ffffff);
            color: #333;
        }
        .header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: white;
    padding: 15px 30px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    position: relative;
}

.header h4 {
    font-size: 32px; /* Increase size */
    font-weight: bold;
    color: #111083;
    text-align: center;
}


.imglogo {
    width: 100px;
    height: 70px;
}

.nav-title {
    position: absolute;
    left: 50%;
    transform: translateX(-50%);
    color: #111083;
    font-weight: bold;
    font-size: 24px;
    margin: 0;
}

        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #111083;
        }
        .imglogo{
            width: 100px;
            height: 70px;
            margin:15px;
        }
        .filters, .show-cards {
            margin: 20px;
        }
        .filters {
            max-width: 300px;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }
        .filters h2 {
            color: #111083;
        }
        .filter-group {
            margin-bottom: 15px;
        }
        .show-cards {
             display: flex;
            flex-wrap: wrap;
            gap: 20px;
            
        }
        .show-card {
            width: 200px;
            background: white;
           
            /* border-radius: 10px; */
            /* box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1); */
            border:none;
            overflow: hidden;
           
        }
        .show-card img {
            width: 100%;
            height: 400px; /* Larger height */
            object-fit: cover;
        }
        .show-card .title {
            font-size: 18px;
            color: #000000;
            font-weight: bold;
            padding: 10px;
        }
        .show-card .description {
            padding: 10px;
            color: #555;
        }
        button {
            background: #111083;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }
        button:hover {
            background: #111083;
        }.title { width:40vw;}
        .blue{color: #111083;

        }
        .details3{
            background: #111083;
            color: white;
            margin-left: 50px;
        } 
        .login-btn {
            background: #111083;
            color: white;
            border: none; padding: 12px 30px;
            border-radius: 30px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s ease;
            margin-left: 950px;
         
        }

        .login-btn:hover {
            background: #111083;
            transform: scale(1.05);
        } 

        .navbar-heading {
    color: #111083;
    font-weight: bold;
    text-align: center;
    flex-grow: 1;
}

    </style>
</head>
<body>
   

<div class="header">
    <img src="http://nextevent.co/wp-content/uploads/2019/02/next_event_logo_light.png" class="imglogo">
    <h4 class="nav-title">Events in <?php echo htmlspecialchars($_GET['City']); ?></h4>
</div>


    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3">
                <div class="filters">
                    <h2>Filter Events</h2>
                    <form method="GET" action="">
                        <div class="filter-group">
                            <label for="price"><b>Price:</b></label>
                            <select id="price" name="price" class="form-control">
                                <option value="">All</option>
                                <option value="below_200" <?= ($filterPrice == 'below_200') ? 'selected' : ''; ?>>Below 200</option>
                                <option value="200_500" <?= ($filterPrice == '200_500') ? 'selected' : ''; ?>>200-500</option>
                                <option value="above_500" <?= ($filterPrice == 'above_500') ? 'selected' : ''; ?>>Above 500</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label for="genre"><b>Genre:</b></label>
                            <select id="genre" name="genre" class="form-control">
                                <option value="">All</option>
                            
                                <option value="Kids" <?= ($filterGenre == 'Kids') ? 'selected' : ''; ?>>Kids</option>
                                <option value="Tours" <?= ($filterGenre == 'Tourists') ? 'selected' : ''; ?>>Tourists</option>
                                <option value="Festivals" <?= ($filterGenre == 'Festivals') ? 'selected' : ''; ?>>Festivals</option>
                                <option value="Workshops" <?= ($filterGenre == 'Workshops') ? 'selected' : ''; ?>>Workshops</option>
                                <option value="Exhibitions" <?= ($filterGenre == 'Exhibitions') ? 'selected' : ''; ?>>Exhibitions</option>
                                <option value="Adventure" <?= ($filterGenre == 'Adventure') ? 'selected' : ''; ?>>Adventure</option>
                                <option value="Performances" <?= ($filterGenre == 'Performances') ? 'selected' : ''; ?>>Performances</option>
                                <option value="Music Shows" <?= ($filterGenre == 'Music Shows') ? 'selected' : ''; ?>>Music Shows</option>
                                <option value="Comedy Shows" <?= ($filterGenre == 'Comedy Shows') ? 'selected' : ''; ?>>Comedy Shows</option>
                                <option value="Food & Drinks" <?= ($filterGenre == 'Food & Drinks') ? 'selected' : ''; ?>>Food & Drinks</option>


                                
                            </select>
                        </div>
                        <div class="filter-group">
                            <label for="date"><b>Date:</b></label>
                            <input type="date" id="date" name="date" class="form-control" value="<?= $filterDate; ?>">
                        </div>
                        <?php
                        if(isset($_GET['City']) && ($_GET['City']!=''))
                        { ?>
                        <input type="hidden" name="City" value="<?php echo $_GET['City'];?>">
                        <?php } ?>
                        <button type="submit">Apply Filters</button>
                    </form>
                </div>
            </div>
            <div class="col-md-9">
                <div class="show-cards">
                    <?php if (!empty($filteredEvents)): ?>
                        <?php foreach ($filteredEvents as $event): ?>
                            <div class="show-card">
                          

                                <img src="<?= htmlspecialchars($event['image']); ?>" alt="<?= htmlspecialchars($event['title']); ?>" style="height: 300px;width:300;">

                                <div class="title"><?= htmlspecialchars($event['title']); ?></div>
                                <div class="description"><?= htmlspecialchars($event['description']); ?></div>
                               
                                <a href="event-details.php?event=<?= $event['id']; ?>" class=" btn details3" style="margin-left: 62px;">Details</a>

                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No events found matching your filters.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>