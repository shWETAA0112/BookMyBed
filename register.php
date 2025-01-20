<?php
$message = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conn = new mysqli("localhost", "root", "root", "bookmybed");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $name = $_POST['name'];
    $age = $_POST['age'];
    $contact = $_POST['contact'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "<p style='color: red;'>Invalid email format!</p>";
    } elseif (!preg_match("/^\+?[0-9]{10,15}$/", $contact)) {
        $message = "<p style='color: red;'>Invalid contact number format!</p>";
    } else {
        $stmt = $conn->prepare("INSERT INTO patients (patient_name, patient_age, patient_contact, email, password) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sisss", $name, $age, $contact, $email, $password);

        if ($stmt->execute()) {
            $message = "<p style='color: green; padding: 8px; border-radius: 8px;'>Registration successful! <a href='login.php'>Login here</a>.</p>";
        } else {
            $message = "<p style='color: red; padding: 8px; border-radius: 8px;'>Error: " . $stmt->error . "</p>";
        }

        $stmt->close();
    }

    $conn->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
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
            background-color: white; 
        }
    </style>
</head>
<body>
    <section>
    <h2>CREATE ACCOUNT</h2>
    <form method="POST" action="register.php">
        <label>Name:</label>
        <input type="text" name="name" placeholder="Enter Name" required><br>
        <label>Age:</label>
        <input type="number" name="age" placeholder="Enter Age" required><br>
        <label>Contact:</label>
        <input type="tel" id="contact" name="contact" placeholder="Enter phone number" pattern="^(\+91)?[6-9]\d{9}$" required>><br>
        <label>Email:</label>
        <input type="email" name="email" placeholder="Enter Email" required><br>
        <label>Password:</label>
        <input type="password" name="password" placeholder="Enter Passwaord" required><br>
        <button type="submit">Register</button>
    </form>
    <div>
    <?php echo $message; ?>
    </div>
    </section>
</body>
</html>
