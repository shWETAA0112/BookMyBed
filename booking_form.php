<?php
// booking_form.php
session_start();

if (!isset($_SESSION['patient_id'])) {
    // Redirect to login if the user is not logged in
    header("Location: login.php");
    exit();
}

// Database connection
$host = "localhost";
$username = "root";
$password = "root";
$dbname = "bookmybed";

$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$bed_id = $_GET['id'] ?? null;

if (!$bed_id) {
    echo "Error: No bed selected.";
    exit();
}

$patient_id = $_SESSION['patient_id'];

// Fetch bed details from the database
$query = "SELECT * FROM beds WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $bed_id);
$stmt->execute();
$result = $stmt->get_result();
$bed = $result->fetch_assoc();

if (!$bed || $bed['status'] !== 'available') {
    echo "Error: Bed is not available for booking.";
    exit();
}

$success_message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient_name = $_POST['name'];
    $patient_contact = $_POST['patient_contact'];
    $booking_date = date('Y-m-d');

    // Update beds table to mark the bed as booked
    $update_bed_query = "UPDATE beds SET status = 'unavailable', booking_date = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_bed_query);
    $update_stmt->bind_param('si', $booking_date, $bed_id);

    // Update patients table with booking details
    $update_patient_query = "UPDATE patients SET booking_date = ?, bed_id = ? WHERE id = ?";
    $patient_stmt = $conn->prepare($update_patient_query);
    $patient_stmt->bind_param('sii', $booking_date,$bed_id, $patient_id);

    
    if ($update_stmt->execute() && $patient_stmt->execute()) {
        $success_message = "<p style='color: green; text-align:center;'><strong>Booking successful! Bed Number: {$bed['bed_number']}, Date: $booking_date!</strong></p>
        <p style='text-align:center;'>Check Your Appointments on the <a href='dashboard.php'>Dashboard</a>.</p>";
    } else {
        $success_message = "<p style='color: red; text-align:center;'><strong>Error: " . htmlspecialchars($conn->error) . "</strong></p>";
    }

    $update_stmt->close();
    $patient_stmt->close();
}

$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book a Bed</title>

    <style>
        *{
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            
        }

        body{
            width: 100%;
            min-height: 100vh;
            color: white;
            /* background-color:rgb(67, 170, 186); */
            /* background-image: linear-gradient(45deg, rgb(159, 227, 238) ,rgb(150, 199, 222)); */
            background-image: url("https://postpear.com/wp-content/uploads/2022/09/hospital-website-features.jpg");
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-size: cover;
            background-position: center;
            /* display: flex;
            flex-direction:column;
            align-items: center;
            justify-content:center; */
      

        }
        section{
            width: 100%;
            /* min-height: 100vh; */
            display: flex;
            flex-direction:column;
            align-items: center;
            justify-content:center;
            gap: 50px;
           
        }

        form{
            display: flex;
            flex-direction:column;
            /* align-items: center; */
            justify-content:center;
            box-shadow: 5px 5px 20px 2px rgba(0, 0, 0, 0.62); 
            border-radius: 10px;
            width: 20%;
            padding: 20px;
        }

        @media (max-width: 1024px) {
            form{
                width: 30%;
            }
        }
        
        @media (max-width: 768px) {
            form{
                width: 40%;
            }
        }

        input{
            padding: 8px;
        }

        button{
            padding: 8px;
            border:none;
            background-image: linear-gradient(45deg, rgb(6, 211, 243) ,rgb(163, 132, 249));
            /* background-color:rgb(3, 19, 24); */
        }

        a{
            padding: 8px;
            border:none;
            background-image: linear-gradient(45deg, rgb(6, 211, 243) ,rgb(163, 132, 249));
            text-decoration: none;
        }
        </style>
</head>
<body>
    <section>
    <h1>Booking Form</h1>
    <p>Bed Number: <?php echo htmlspecialchars($bed['bed_number']); ?></p>

    <form method="POST" action="">
        <label for="name">Name:</label>
        <input type="name" id="name" name="name" required><br>

        <label for="patient_contact">Contact:</label>
         <input type="tel" id="patient_contact" name="patient_contact" placeholder="Enter phone number" pattern="^(\+91)?[6-9]\d{9}$" required><br>

        <button type="submit">Confirm Booking</button>
    </form>

    <a href="index.php">Cancel</a>

    <!-- Display the success or error message -->
    <?php if (!empty($success_message)): ?>
        <?php echo $success_message; ?>
    <?php endif; ?>
    </section>
</body>
</html>
