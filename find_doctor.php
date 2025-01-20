<?php
// Database connection
$host = 'localhost';
$user = 'root';
$password = 'root';
$database = 'bookmybed';

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch doctors data

$sql = "SELECT id, name, specification, education, image_path, about FROM doctors";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find Doctors</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }

        header {
    /* width: 100%; */
    background-color: #fff;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: sticky;
    top: 0;
    z-index: 1000;
    padding: 10px 20px;
}

header h1 {
    color: #d9534f;
    margin: 0;
    font-size: 1.5rem;
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

        .doctor-container {
            display: flex;
            /* flex-wrap: wrap; */
            flex-direction: column;
            justify-content: center;
            align-items:  center;
            margin: 20px;
        }

        .doctor-card {
            display: flex;
            width: 60%;
            background-color: #e8ffe8;
            border: 1px solid #d4d4d4;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin: 15px;
            padding: 20px;
            gap: 20px;
            /* width: 300px; */
            /* text-align: center; */
        }

        .doctor-card {
            display: flex;
        }

        .doctor-card img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 15px;
        }

        .doctor-card h2 {
            font-size: 20px;
            margin-bottom: 10px;
            color: #333;
        }

        .doctor-card p {
            font-size: 14px;
            margin: 5px 0;
        }

        .book-button {
            display: inline-block;
            margin-top: 15px;
            padding: 10px 15px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .book-button:hover {
            background-color: #45a049;
        }

        @media (max-width: 768px) {
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

            .doctor-card {
                width: 90%;
            }
        }

        @media (max-width: 570px) {
            .doctor-card {
                flex-direction: column;
                gap: 0;
            }

            .doctor-card img {
            width: 200px;
            height: 200px;
            margin-bottom: 0px;
        }
        }
    </style>
</head>
<body>
<header>
        <h1>Find Doctors</h1>
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
    
    <div class="doctor-container">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $imagePath = !empty($row['image_path']) ? htmlspecialchars($row['image_path']) : 'uploads/profile.png';
                echo "<div class='doctor-card'>";
                echo "<img src='$imagePath' alt='Doctor Image'>";
                echo "<div class='doctor-info'>";
                echo "<h2>" . htmlspecialchars($row['name']) . "</h2>";
                echo "<p><strong>Specialization:</strong> " . htmlspecialchars($row['specification']) . "</p>";
                echo "<p><strong>Education:</strong> " . htmlspecialchars($row['education']) . "</p>";
                echo "<p><strong>About:</strong> " . htmlspecialchars($row['about']) . "</p>";
                echo "<a class='book-button' href='doctor_booking_form.php?id=" . urlencode($row['id']) . "'>Book Appointment</a>";
                // echo "<a href='doctor_booking_form.php?doctor_id={$row['id']}'>Book Appointment</a>";
                echo "</div>";
                echo "</div>";
            }
        } else {
            echo "<p>No doctors found.</p>";
        }
        ?>
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

<?php
$conn->close();
?>
