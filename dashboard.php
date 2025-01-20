<?php
session_start();
if (!isset($_SESSION['patient_id'])) {
    header("Location: login.php");
    exit();
}

// Database connection
$conn = new mysqli("localhost", "root", "root", "bookmybed");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch patient details
$patient_id = $_SESSION['patient_id'];
$stmt = $conn->prepare("SELECT patient_name FROM patients WHERE id = ?");
$stmt->bind_param("i", $patient_id);
$stmt->execute();
$stmt->bind_result($patient_name);
$stmt->fetch();
$stmt->close();

// Fetch bed booking details
$bed_query = $conn->prepare("
    SELECT beds.id AS bed_id, beds.bed_number, beds.status, patients.booking_date 
    FROM beds
    INNER JOIN patients ON patients.bed_id = beds.id
    WHERE patients.id = ?
");
$bed_query->bind_param("i", $patient_id);
$bed_query->execute();
$bed_result = $bed_query->get_result();
$bed_booking = $bed_result->fetch_assoc();
$bed_query->close();

// Fetch doctor appointment details
$doctor_query = $conn->prepare("
    SELECT doctor_appointments.doctor_id, doctors.name AS doctor_name, doctor_appointments.patient_contact 
    FROM doctor_appointments
    INNER JOIN doctors ON doctor_appointments.doctor_id = doctors.id
    WHERE doctor_appointments.patient_id = ?
");
$doctor_query->bind_param("i", $patient_id);
$doctor_query->execute();
$doctor_result = $doctor_query->get_result();
$appointments = $doctor_result->fetch_all(MYSQLI_ASSOC);
$doctor_query->close();

$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <style>
         *{
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            
        }

        body{
            width: 100%;
            min-height: 100vh;
            /* color: white; */
            background-color: white;
             display: flex;
            flex-direction:column;
            align-items: center;
            /* justify-content:center;             */
        }

        body > h2{
            font-size: 3em;
            margin-bottom: 1em;
            letter-spacing: 3px;
            font-family: "Montserrat", serif;
            font-optical-sizing: auto;
            font-weight: 450;
            font-style: normal;
            /* background-color: #d9534f; */
        }

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
            color:red;
        }

        a{
            text-decoration: none;
            background-color:  #d9534f;
            color:white;
            padding: 7px;
            border-radius: 5px;

        }

        .button{
            display: flex;
            /* flex-direction: column; */
            gap: 20px;
        }

        section{
            display: flex;
            flex-direction: column;
            gap: 15px;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>
<body>
    <header>
        <h1>Dashboard</h1>
    </header>
    <h2>Welcome, <?php echo htmlspecialchars($patient_name); ?>!</h2>
    
    <section>
    <div>
    <h2>Your Bed Booking:</h2>
    <?php if ($bed_booking): ?>
        <p>Bed ID: <?php echo $bed_booking['bed_id']; ?></p>
        <p>Room Number: <?php echo $bed_booking['bed_number']; ?></p>
        <p>Status: <?php echo $bed_booking['status']; ?></p>
        <p>Booking Date: <?php echo $bed_booking['booking_date']; ?></p>
    <?php else: ?>
        <p>You have not booked any beds.</p>
    <?php endif; ?>
    </div>
    <div>
    <h2>Your Doctor Appointments:</h2>
    <?php if ($appointments): ?>
        <ul>
            <?php foreach ($appointments as $appointment): ?>
                <li>
                    Doctor ID: <?php echo $appointment['doctor_id']; ?>,
                    Doctor Name: <?php echo $appointment['doctor_name']; ?>,
                    <!-- Appointment Time: <?php echo $appointment['appointment_time']; ?>, -->
                    <!-- Contact: <?php echo $appointment['patient_contact']; ?> -->
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>You have not booked any doctor appointments.</p>
    <?php endif; ?>
    </div>
    

    <div class="button">
    <p><a href="BookMyBed_interface.php">Return To Homepage</a></p>
    <p><a href="logout.php">Logout</a></p>
    </div>
    </section>
</body>
</html>
