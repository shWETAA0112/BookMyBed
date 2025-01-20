<?php
session_start();
// $_SESSION['email'] = $user_email; 
$message = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conn = new mysqli("localhost", "root", "root", "bookmybed");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password FROM patients WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        $stmt->bind_result($id, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['patient_id'] = $id;
            header("Location: dashboard.php");
            exit();
        } else {
            $message = "<p style='color: red;'>Invalid email or password!</p>";
        }
    } else {
        $message = "<p style='color: red;'>Invalid email or password!</p>";
    }

    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
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
    </style>
</head>
<body>
    <section>
    <h2>Login</h2>
    <form method="POST" action="login.php">
        <label>Email:</label>
        <input type="email" name="email" placeholder="Enter Email" required><br>
        <label>Password:</label>
        <input type="password" name="password" placeholder="Enter Passwaord" required><br>
        <button type="submit">Login</button>
    </form>
    <?php echo $message; ?>
    </section>
</body>
</html>
