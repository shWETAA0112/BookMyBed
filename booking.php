<?php

session_start(); // Start the session

// Check if the patient is logged in
if (!isset($_SESSION['patient_id'])) {
    header("Location: login.php");
    exit();
}

$message = ""; // Initialize an empty message variable


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Database connection
    $conn = new mysqli("localhost", "root", "root", "bookmybed");

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Retrieve form data
    $bed_id = $_POST['bed_id'];
    $patient_name = $_POST['patient_name'];
    $patient_age = $_POST['patient_age'];
    $patient_contact = $_POST['patient_contact'];
    

    // Insert patient record using prepared statements
    // $patient_id = $_SESSION['patient_id'];
    $hospital_id = $_SESSION['hospital_id'];
    $hospital_name = $_SESSION['hospital_name'];
    $bed_id = $_POST['bed_id']; // Example POST data from the booking form
    $stmt = $conn->prepare("INSERT INTO patients (bed_id, hospital_id,  hospital_name) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $bed_id, $hospital_id, $hospital_name);

    if ($stmt->execute()) {
        // Update bed status
        $update_stmt = $conn->prepare("UPDATE beds SET status = 'unavailable' WHERE id = ?");
        $update_stmt->bind_param("i", $bed_id);
        $update_stmt->execute();

        $message = "<p style='color: green; text-align:center;'><strong>Booking Successful! Bed has been reserved for $patient_name and ID for your Bed is $bed_id</strong>.</p>
        <p style='text-align:center;'>Go Back To The Home Page <a href='index.php'>Click Here.</a></p>";
    } else {
        echo "<p>Error: " . $stmt->error . "</p>";
    }

    $stmt->close();
    $conn->close();
} else {
    $bed_id = $_GET['bed_id'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Form</title>
    <style>

        *{
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: Arial, sans-serif;
}

header{
            /* background-color:rgba(253, 159, 65, 0.285); */
    background: linear-gradient(to right, #f9da9b,hsl(42, 82%, 66%));
            text-align: center;
    padding: 10px;
    width: 100%;

}

body{
    min-height: 100vh;
    display: flex;
    /* justify-content: center; */
    align-items: center;
    flex-direction:column;
}

.container {
    width: 30%;
    margin: 100px auto 30px;
    /* background: white; */
    background: linear-gradient(to right, #f9da9b,hsl(42, 82%, 66%));
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    display: flex;
    justify-content: center;
    flex-direction: column;
    align-items: center;
    /* align-items: center; */
}

form{
    display: flex;
    flex-direction:column;
    justify-content: center;
    align-items: center;
    gap: 10px;
}

button{
    padding: 10px 20px;
    background-color: orange;
            border: none;
            border-radius: 5px;
}

input{
    padding: 10px;
    width: 150%;
}

p{
    font-size: 20px;
}


@media (max-width: 1020px) {

    .container {
        width: 40%;
        
    }
}

@media (max-width: 768px) {

.container {
    width: 50%;
    
}
 p{
    font-size: 15px;
}
}

@media (max-width: 600px) {

.container {
    width: 95%;
    
}
p{
    font-size: 12px;
}
}
    </style>
</head>
<body>
    <header>
    <h2>Bed Booking Form</h2>
    </header>
    <section class="container">

    <form method="POST" action="booking_form.php">
    <input type="hidden" name="bed_id" value="<?php echo $bed_id; ?>">
    <label for="name">Name</label>
    <input type="text" name="patient_name" id="name" required><br>
    <label for="age">Age</label>
    <input type="number" name="patient_age" id="age" required><br>
    <label for="contact">Contact</label>
    <input type="text" name="patient_contact" id="contact" required><br>
    <button type="submit">Book Bed</button>
</form>
    </section>
    <div>
              <!-- Display the success/error message below the form -->
              <?php echo $message; ?>
              </div>
</body>
</html>
