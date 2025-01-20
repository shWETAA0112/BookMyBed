<?php
session_start();
$conn = new mysqli("localhost", "root", "root", "BookMyBed");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ensure `hospital_id` is passed in the URL
if (isset($_GET['hospital_id']) && isset($_GET['hospital_name'])) {
    $_SESSION['hospital_id'] = $_GET['hospital_id'];
    $_SESSION['hospital_name'] = urldecode($_GET['hospital_name']);
}

if (!isset($_SESSION['hospital_id']) || !isset($_SESSION['hospital_name'])) {
    die("Hospital not selected! Please go back and choose a hospital.");
}

$hospital_id = $_SESSION['hospital_id'];
$hospital_name = $_SESSION['hospital_name'];


// Fetch bed data from the database for the selected hospital
// $sql = "SELECT id, bed_number, status FROM beds WHERE hospital_id = $hospital_id";
$sql = "SELECT id, bed_number, status FROM beds";
$result = $conn->query($sql);

// Initialize variables for bed buttons and counts
$available_count = 0;
$unavailable_count = 0;
$bed_buttons = "";

// Generate the bed grid dynamically
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $bed_id = $row['id'];
        $bed_number = $row['bed_number'];
        $status = $row['status'];

        // Count available/unavailable beds
        if ($status === 'available') {
            $available_count++;
        } else {
            $unavailable_count++;
        }

        // Create bed buttons
        $bed_buttons .= '<button class="bed ' . ($status === 'unavailable' ? 'unavailable' : '') . '" ';
        $bed_buttons .= ($status === 'available') ? "onclick=\"location.href='booking_form.php?id=$bed_id'\"" : "disabled";
        $bed_buttons .= '>';
        $bed_buttons .= $bed_number;
        $bed_buttons .= '</button>';
    }
} else {
    $bed_buttons = "<p>No beds available in the system for this hospital. Please initialize the database.</p>";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BookMyBed - Bed Availability</title>
    <style>
        /* Basic styling with Flexbox */
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background-color: rgb(233, 210, 158);
            margin: 0;
            padding: 0;
        }
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            background-color: white;
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
        }
        header h1 {
            font-size: 24px;
            color: black;
        }
        .bed-grid {
            display: flex;
            grid-template-columns: repeat(5, 1fr); /* 5 columns */
            flex-wrap: wrap;
            justify-content: center;
            margin: 20px;
            gap: 10px;
        }
        .bed {
            width: 80px;
            height: 50px;
            background-color: green;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
            line-height: 50px; /* Vertically center text */
        }

        header .nav-buttons {
            display: flex;
            gap: 10px;
        }

        header .nav-buttons button {
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
}

        @media (max-width:320px) {
            .bed {
            width: 60px;
            }
        }
        .bed.unavailable {
            background-color: red;
            cursor: not-allowed;
        }
        .summary {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <header>
        <h1>Book<span style="color: red;">My</span>Bed</h1>
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

    <main>
        <h2>Bed Availability</h2>
        <p>Select an available bed to proceed with booking.</p>
        <div class="bed-grid">
            <?php echo $bed_buttons; ?>
        </div>
        <div class="summary">
            <p><strong>Available Beds:</strong> <?php echo $available_count; ?></p>
            <p><strong>Unavailable Beds:</strong> <?php echo $unavailable_count; ?></p>
        </div>
    </main>
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
