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


// Fetch events with IDs from 1 to 12
$sql = "SELECT * FROM events WHERE id BETWEEN 1 AND 12";
$result = $conn->query($sql);
$conn->close();

// Social media links
$facebook_link = "https://www.facebook.com";
$instagram_link = "https://www.instagram.com";
$twitter_link = "https://www.twitter.com";
?>

<!DOCTYPE html>
<html>
<head>
    <title>Event List</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.CSS">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
   
    <style>
          body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(to bottom, #f8f9fa, #ffffff);
            color:#333;
            overflow-x: hidden;
        }

        .header {
            background: #ffffff;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .header:hover {
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .logo-container {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .img1 {
            width: 100px;
            height: 70px;
        }

        .search-bar-container {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .search-bar-select {
            width: 200px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 30px;
            font-size: 16px;
            transition: all 0.3s ease;
            appearance: none;
            background-color: #ffffff;
        }

        .search-bar-select:focus {
            outline: none;
            border-color: #111083;
            box-shadow: 0 0 8px #111083(232, 168, 3, 0.5);
        }

        .search-button {
            padding: 10px 20px;
            background: #111083;
            color: white;
            border: none;
            border-radius: 30px;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .search-button:hover {
            background:#111083;
            transform: scale(1.05);
        }

        .header-buttons {
            display: flex;
            gap: 20px;
        }

        .about-btn, .login-btn {
            padding: 12px 30px;
            border-radius: 30px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .about-btn {
            background: transparent;
            color: #111083;
            border: 2px solid #111083;
        }

        .about-btn:hover {
            background: #111083;
            color: white;
        }

        .login-btn {
            background: #111083;
            color: white;
            border: none;
        }

        .login-btn:hover {
            background: #111083;
            transform: scale(1.05);
        }

        .dropdown-content {
            display: none;
            background-color: #fff;
            position: absolute;
            top: 60px;
            left: 0;
            width: 100%;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            color: #333;
        }

        .dropdown-content.show {
            display: block;
        }

        .dropdown-content h2 {
            color: #111083;
            font-size: 24px;
        }

        .scrollable-poster-section {
            margin-top: 30px;
            overflow-x: auto;
            padding: 20px 0;
            display: flex;
            gap: 20px;
            background-color: #f8f9fa;
        }

        .poster-card {
            width: 300px;
            height: 180px;
            background-color: #ccc;
            border-radius: 10px;
            flex-shrink: 0;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .poster-card:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 25px rgba(0, 0, 0, 0.2);
        }

        .poster-card img {
            width: 100%;
            height: 100%;
            border-radius: 10px;
        }

        .poster-card-caption {
            text-align: center;
            font-size: 16px;
            color: #333;
            margin-top: 10px;
        }

        .social-icons {
            display: flex;
            justify-content: center;
            gap: 40px;
            margin-bottom: 50px;
            margin-top: 50px;
            /* background-color: #ECECEC; */
        }

        .social-icons a {
            font-size: 30px;
            color: #333;
            transition: all 0.3s ease;
        }

        .social-icons a:hover {
            color: #111083;
            transform: scale(1.2);
        }

        /* Added styles for the "Recommended for you" heading */
        .recommended-title {

            color: #111083; /* Dark blue text */
            padding-left: 45px; /* Adds space on the left side */
            padding-top: 30px; /* Adds space on the top side */
        }

        .recommended-heading {
            
            font-size: 28px;
            font-weight: 600;
            color: #111083;
            margin-top: 30px;
            margin-left:22px;
            margin-bottom: 10px;
        }

        .recommended-posters {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-top: 20px;
        }
        .singimg{
            height: 300px;
        }
        .komal{
            color:#111083;
        }
        .imgnext{
            height:100px;
            width: 150px;
            padding-bottom:50px;
            padding-right: 60px;
        }
        .facebook2{
            padding-bottom: 100px;
        }
        .button {
  background-color:#111083;
  border: none;
  color: white;
  /* padding: 15px 32px; */
  text-align: center;
  text-decoration: none;
  /* display: inline-block; */
  font-size: 15px;
  width:100px;
  height: 35px;
  margin-left: 40px;
  margin-top: 10px;

  /* cursor: pointer; */
}
.open-button {
            padding: 10px 20px;
            background-color: #111083;
            color: white;
            border: none;
            /* cursor: pointer; */
        }
        /* Style for the modal */
        .modal {
            display: none; 
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
            padding-top: 60px;
        }
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }
        .close-button {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close-button:hover,
        .close-button:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }


        .event-container {
    display: flex;
    flex-wrap: wrap;
    gap: 20px; /* Space between cards */
}

.event-card {
    flex: 1 1 calc(25% - 20px); /* 4 cards per row, subtract gap for spacing */
    box-sizing: border-box;
    background: #fff;
    padding: 15px;
    border-radius: 8px;
    text-align: center;
}
.event-container {
    display: grid;
    grid-template-columns: repeat(4, 1fr); /* 4 columns */
    gap: 20px; /* Space between cards */
    margin-top: 25px;
}

.event-card {
    background: #fff;
    padding: 15px;
    border-radius: 8px;
    text-align: center;
}
.details3{
            background: #111083;
            color: white;
        } 
        .social-icons {
        text-align: center; /* Align icons to the center */
        margin-bottom: 20px; /* Add space below social icons */
    }

    .social-icons h5 {
        text-align: center; /* Center align the footer text */
        font-size: 14px; /* Make the font size smaller */
        margin-top: 10px; /* Add space above the footer text */
        
    }

    .social-icons a {
        margin: 0 10px; /* Add spacing between the icons */
    }

    .fab {
        font-size: 24px; /* Adjust the icon size */
    }
    

    </style>
     <script>
        function toggleDropdown() {
            var dropdownContent = document.getElementById("aboutDropdown");
            dropdownContent.classList.toggle("show");
        }
    </script>
</head>
<body>
<div class="header">
        <div class="logo-container">
            <img src="http://nextevent.co/wp-content/uploads/2019/02/next_event_logo_light.png" class="img1">
            <!-- Search Bar with Dropdown -->
            <form action="shows.php" method="GET" class="search-bar-container">
                <select name="City" class="search-bar-select" required>
                    <option value="">Select a Location</option>
                    <option value="Ahmedabad">Ahmedabad</option>
                    <option value="Bengaluru">Bengaluru</option>
                    <option value="Chandigarh">Chandigarh</option>
                    <option value="Chennai">Chennai</option>
                    <option value="Delhi">Delhi</option>
                    <option value="Hyderabad">Hyderabad</option>
                    <option value="Kolkata">Kolkata</option>
                    <option value="Mumbai">Mumbai</option>
                    <option value="Pune">Pune</option>

                </select>
                <button type="submit" class="search-button">Search</button>
            </form>
        </div>
        <div class="header-buttons">
            
             <button class="about-btn" onclick="document.getElementById('myModal').style.display='block'">About Us</button>
            <a href="login.html" class="login-btn">Admin Login</a>
        </div>
    </div>

    <!-- The Modal -->
    <div id="myModal" class="modal">
    
      
         <div class="modal-content"> 
            <span class="close-button" onclick="document.getElementById('myModal').style.display='none'">&times;</span>
            <h4>Welcome to TheNextEvent!</h4>
        <ul>Your ultimate destination for discovering and booking unforgettable events!</ul>
        <h5>Who We Are</h5>
        <ul>At TheNextEvent, we're a team of passionate event enthusiasts committed to connecting people with the moments that matter. We believe every event has the power to create lasting memories, and our mission is to ensure you never miss out on the action.</ul>
        <h5>What We Offer</h5>
        <ul>
            <li>Wide Range of Events</li>
            <li>User-Friendly Platform</li>
            <li>Secure Transactions</li>
            <li>Exceptional Customer Support</li>
        </ul>
        <h5>Contact with us</h5>
        <ul>
            <li>Call us at +91 9051762145</li>
            <li>E-mail us at support@thenextevent.com</li>
        </ul>
        </div>
    </div>
    <!-- Scrollable Posters Section -->
    <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img src="https://assets-in.bmscdn.com/promotions/cms/creatives/1735281941252_ajayatulweb.jpg" class="d-block w-100 singimg" alt="...">
    </div>
    <div class="carousel-item">
      <img src="https://assets-in.bmscdn.com/promotions/cms/creatives/1736836844818_luckyali1240x300v1.jpg" class="d-block w-100 singimg" alt="...">
    </div>
    <div class="carousel-item">
      <img src="https://assets-in.bmscdn.com/promotions/cms/creatives/1736154368702_dailykakaamhaibyweb.jpg" class="d-block w-100" alt="...">
    </div>
    <div class="carousel-item">
      <img src="https://assets-in.bmscdn.com/promotions/cms/creatives/1740738644683_markopaweekend1240x300.jpg" class="d-block w-100" alt="...">
    </div>
    <div class="carousel-item">
      <img src="https://assets-in.bmscdn.com/promotions/cms/creatives/1736782925161_chetaswebholipune.jpg" class="d-block w-100" alt="...">
    </div>

  </div>
 <button class="carousel-control-prev" type="button" data-target="#carouselExampleControls" data-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-target="#carouselExampleControls" data-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </button>
</div>

<h2 class="recommended-title">Recommended for you</h2>


<div class="event-container">
    <?php if ($result->num_rows > 0): ?>
        <?php while ($event = $result->fetch_assoc()): ?>
            <div class="event-card">
                <img src="<?= htmlspecialchars($event['image']); ?>" class="event-img" alt="<?= htmlspecialchars($event['title']); ?>"style="height: 350px;width:300;">
                <h4 class="event-title"><?= htmlspecialchars($event['title']); ?></h4>
               
                <p class="event-details"><?= htmlspecialchars($event['description']); ?></p>
                <a href="event-details.php?event=<?= $event['id']; ?>" class="btn details3" >Details</a>
            </div>
        <?php endwhile; ?>
    <?php endif; ?>
</div>

     
    <!-- Social Media Icons -->
     <div class="social-icons" style="display: flex; justify-content: center; gap: 15px; padding-top: 20px;">
    <a href="<?php echo $facebook_link; ?>" target="_blank"><i class="fab fa-facebook-square rounded-circle"></i></a>
    <a href="<?php echo $instagram_link; ?>" target="_blank"><i class="fab fa-instagram"></i></a>
    <a href="<?php echo $twitter_link; ?>" target="_blank"><i class="fab fa-twitter-square"></i></a>
    <a href="https://www.youtube.com/user/YourChannelName" target="_blank"><i class="fab fa-youtube"></i></a>
    <a href="https://www.pinterest.com/YourProfileName" target="_blank"><i class="fab fa-pinterest"></i></a>
    </div>

    <!-- Footer text -->
    <div style="display: flex; justify-content: center; align-items: center; height: 2vh; padding-top: 0px; flex-direction: column;">
    <h6 style="margin: 0; text-align: center;">@2025 TheNextEvent. All Rights Reserved.<br> Anisha Dalal</h6>
    </div>
    
</div>
</body>
</html>