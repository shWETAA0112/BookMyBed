<?php

session_start(); // Start the session

// Check if the patient is logged in
if (!isset($_SESSION['patient_id'])) {
    header("Location: login.php");
    exit();
}

$message = ""; // Initialize an empty message variable
$doctor_name = ""; // Variable to store the doctor's name

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Database connection
    $conn = new mysqli("localhost", "root", "root", "bookmybed");

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Retrieve form data
    $doctor_id = $_POST['doctor_id'];
    $patient_name = $_POST['patient_name'];
    $patient_age = $_POST['patient_age'];
    $patient_contact = $_POST['patient_contact'];

    // Fetch the doctor's name based on the ID
    $result = $conn->query("SELECT name FROM doctors WHERE id = $doctor_id");
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $doctor_name = $row['name'];
    } else {
        die("<p style='color: red; text-align:center;'><strong>Error: Doctor not found!</strong></p>");
    }

    // Insert appointment record using prepared statements
    $patient_id = $_SESSION['patient_id'];
    $stmt = $conn->prepare("INSERT INTO doctor_appointments (doctor_id, doctor_name, patient_id, patient_name, patient_age, patient_contact) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("isssis", $doctor_id, $doctor_name, $patient_id, $patient_name, $patient_age, $patient_contact);

    if ($stmt->execute()) {
        $message = "<p style='color: green; text-align:center;'><strong>Appointment Successful for Dr. $doctor_name (ID: $doctor_id)!</strong></p>
        <p style='color: green; 'text-align:center; '><strong>Check Your Appointments <a href='dashboard.php' style='text-align:center;'>Dashboard</strong></a>.</p>";
    } else {
        $message = "<p style='color: red; text-align:center;'><strong>Error: " . $stmt->error . "</strong></p>";
    }

    $stmt->close();
    $conn->close();
} else {
    // Check if doctor_id exists in GET request
    if (isset($_GET['id'])) {
        $doctor_id = $_GET['id'];

        // Fetch the doctor's name
        $conn = new mysqli("localhost", "root", "root", "bookmybed");
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $result = $conn->query("SELECT name FROM doctors WHERE id = $doctor_id");
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $doctor_name = $row['name'];
        } else {
            die("<p style='color: red; text-align:center;'><strong>Error: Doctor not found!</strong></p>");
        }

        $conn->close();
    } else {
        die("<p style='color: red; text-align:center;'><strong>Error: Doctor ID not provided!</strong></p>");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Appointment Form</title>
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
            display: flex;
            flex-direction:column;
            gap: 60px;
            /* align-items: center; */
            /* justify-content:center; */
      

        }
        section{
            width: 100%;
            /* min-height: 100vh; */
            display: flex;
            flex-direction:column;
            align-items: center;
            justify-content:center;
            gap: 20px;
           
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
        @media (max-width: 640px) {
            form{
                width: 60%;
            }
        }
        @media (max-width: 410px) {
            form{
                width: 70%;
            }
        }

        section h2{
            text-align: center;
            padding: 20px;
            font-size: 40px;
            font-weight: 400;
            
        }

        input{
            padding: 8px;
        }

        button{
            padding: 8px;
            border:none;
            background-image: linear-gradient(45deg, rgb(6, 211, 243) ,rgb(163, 132, 249))
            /* background-color:rgb(3, 19, 24); */
        }

        div{
            /* width: 50%; */
            background-color: white; 
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content:center;
        }

        header{
            background-color: #f0f0f0;
            color: black;
            padding:  10px;
        }

        a{
            text-decoration : none
        }
    </style>
</head>
<body>
    <header>
        <h2>Doctor Appointment Form</h2>
    </header>
    <section class="container">
        <form method="POST" action="doctor_booking_form.php">
            <input type="hidden" name="doctor_id" value="<?php echo $doctor_id; ?>">
            <p><strong>Doctor Name:</strong> <?php echo $doctor_name; ?></p>
            <label for="name">Name</label>
            <input type="text" name="patient_name" id="name" required><br>
            <label for="age">Age</label>
            <input type="number" name="patient_age" id="age" required><br>
            <label for="contact">Contact</label>
            <input type="tel" id="contact" name="patient_contact" placeholder="Enter phone number" pattern="^(\+91)?[6-9]\d{9}$" required><br>
            <button type="submit">Book Appointment</button>
        </form>
    </section>
    <div>
        <!-- Display the success/error message below the form -->
        <?php echo $message; ?>
    </div>
</body>
</html>
