<?php
// Start session and include DB connection
session_start();
$conn = new mysqli("localhost", "root", "root", "BookMyBed");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch hospital details from the database
$query = "SELECT * FROM hospitals";
$result = $conn->query($query);

// Handle empty results
if ($result === false || $result->num_rows === 0) {
    $hospitals = []; // No hospitals found
} else {
    $hospitals = $result; // Assign the result object
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find Hospital</title>
    <style>
       /* General styles for layout */
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f9f9f9;
}

/* Header and Navigation */
header {
    width: 100%;
    background-color: #fff;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: sticky;
    top: 0;
    z-index: 1000;
    padding: 10px 0;
}

header h1 {
    color: #d9534f;
    margin: 0;
    font-size: 1.5rem;
}

header .nav-buttons {
    display: flex;
    gap: 10px;
}

header .nav-buttons button {
    width: 120px;
    padding: 0.5em 1em;
    border: none;
    background-color: #d9534f;
    color: #fff;
    border-radius: 5px;
    cursor: pointer;
}

header .nav-buttons button:hover {
    background-color: #c9302c;
}

/* Responsive Navigation Bar (hamburger icon) */
header .hamburger {
    display: none;
    cursor: pointer;
    flex-direction: column;
    gap: 5px;
}

header .hamburger div {
    width: 25px;
    height: 3px;
    background-color: #d9534f;
}

header .nav-buttons {
    display: flex;
    gap: 10px;
}

/* Container styles */
.container {
    max-width: 1200px;
    margin: 2em auto;
    padding: 1em;
}

/* Hospital Cards */
.hospital-card {
    background-color: #e6ffe6;
    border: 1px solid #ccc;
    border-radius: 8px;
    padding: 1em;
    margin-bottom: 1em;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.hospital-card h2 {
    margin: 0;
    color: #333;
}

.hospital-card p {
    margin: 0.5em 0;
    color: #555;
}

.hospital-card img {
            .hospital-card img 
    width: 100px; /* Fixed width */
    height: 100px; /* Fixed height */
   
            object-fit: cover;
            border-radius: 8px;
            margin-right: 1em;
}

.hospital-card button {
    padding: 0.8em 1em;
    border: none;
    /* background-color: #555; */
    background-color: #d9534f;
    color: #fff;
    border-radius: 5px;
    cursor: pointer;
}

.hospital-card button:hover {
    background-color: #333;
}

/* Flexbox for buttons */
.button {
    display: flex;
    flex-direction: column;
    gap: 25px;
}

/* Media Queries for responsiveness */
@media (max-width: 768px) {
    /* Adjust header and navigation for smaller screens */
    header .nav-buttons {
        display: none; /* Hide navigation buttons */
        width: 150px;
        justify-content: center;
        flex-direction: column;
        align-items: center;
        gap: 10px;
        position: absolute;
        top: 60px;
        right: 0;
        background-color: #fff;
        padding: 1em;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    header .hamburger {
        display: flex; /* Show hamburger icon */
    }

    /* When the hamburger icon is clicked, display the nav buttons */
    header .nav-buttons.active {
        display: flex;
    }

    /* Adjust the container layout */
    .container {
        margin: 1em;
        padding: 1em;
    }

    /* Stack the hospital cards in a column for small screens */
    .hospital-card {
        flex-direction: column;
        align-items: flex-start;
    }

    .hospital-card img {
        width: 100%;
        height: auto;
    }
}

@media (max-width: 480px) {
    header h1 {
        font-size: 1.5rem; /* Adjust the font size for small screens */
    }

    .hospital-card button {
        padding: 0.5em;
    }

    .hospital-card img {
                width: 80px;
                height: 80px;
            }
        
}

    </style>
</head>
<body>
<header>
    <h1>BookMyBed</h1>
    <div class="hamburger">
        <div></div>
        <div></div>
        <div></div>
    </div>
        <div class="nav-buttons">
            <a href="find_hospital.php"><button>Find Hospital</button></a>
            <a href="find_doctor.php"><button>Find Doctor</button></a>

            <?php if (isset($_SESSION['patient_id'])): ?>
                <a href="dashboard.php"><button>My Dashboard</button></a>
                <a href="logout.php"><button>Logout</button></a>
            <?php else: ?>
                <a href="login.php"><button>Login</button></a>
                <a href="register.php"><button>Register</button></a>
            <?php endif; ?>
        </div>
    </header>

    <div class="container">
        <h2>According to your location: "Mumbai"</h2>
        <p>We have found the following results:</p>

        <?php if ($hospitals && $hospitals->num_rows > 0): ?>
        <?php while ($row = $hospitals->fetch_assoc()): ?>
            <div class="hospital-card">
            <img src="<?php echo htmlspecialchars($row['image_url']); ?>" alt="Hospital Image">
                <div>
                    <h2><?php echo htmlspecialchars($row['name']); ?></h2>
                    <p><strong>Address:</strong> <?php echo htmlspecialchars($row['address']); ?></p>
                    <p><strong>Contact:</strong> <?php echo htmlspecialchars($row['contact']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($row['email']); ?></p>
                    <p><strong>Specialties:</strong> <?php echo htmlspecialchars($row['specialities']); ?></p>
                </div>
                <div class="button">
                    <!-- Book a Bed Button -->
                    <!-- <form action="index.php" method="GET"> -->
                    <a href="index.php?hospitals_id=<?= urlencode ($row['hospitals_id']); ?>&hospitals_name=<?= urlencode($row['name']); ?>">
    <button type="button">Book My Bed</button>
</a>
                    <!-- </form> -->

                    <!-- Get Directions Button -->
                    <form action="https://www.google.com/maps/search/?api=1" method="GET" target="_blank">
                        <input type="hidden" name="query" value="<?php echo urlencode($row['address']); ?>">
                        <button type="submit">Get Directions</button>
                    </form>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No hospitals found. Please check back later.</p>
    <?php endif; ?>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
    const hamburger = document.querySelector('.hamburger');
    const navButtons = document.querySelector('.nav-buttons');

    hamburger.addEventListener('click', function () {
        navButtons.classList.toggle('active');
    });
});
</script>
</body>
</html>
