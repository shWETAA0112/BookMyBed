<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BookMyBed</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            box-sizing: border-box;
            background-image: url("https://postpear.com/wp-content/uploads/2022/09/hospital-website-features.jpg");
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-size: cover;
            background-position: center;
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

        header .nav-buttons a {
            text-decoration: none;
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

        .container {
            text-align: center;
            margin-top: 2em;
            width: 80%;
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

@media (max-width: 480px) {
    header h1 {
        font-size: 1.5rem; /* Adjust the font size for small screens */
    }
}

        .container h2 {
            font-size: 3em;
            margin-bottom: 1em;
            letter-spacing: 3px;
            font-family: "Montserrat", serif;
            font-optical-sizing: auto;
            font-weight: 450;
            font-style: normal;
            color: white;
        }

        @media (max-width: 1024px) {
            .container h2{
            font-size: 2em;
            }
        }
        /* @media (max-width: 960px) {
            .container h2{
            font-size: 2em;
            }
        } */
       

        .container button {
            padding: 0.7em 1.5em;
            border: none;
            background-color: #d9534f;
            color: #fff;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
        }

        .container button:hover {
            background-color: #c9302c;
        }

        .about {
            margin-top: 100px;
            padding: 1em;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            width: 80%;
            text-align: center;
        }

        .about h3 {
            color: #d9534f;
            margin-bottom: 0.5em;
        }

        .gif-container {
            position: relative;
            text-align: center;
            margin-top: 2em;
            
        }

        .gif-container img {
            width: 300px;
            height: 250px;
            animation: pop 2s infinite;
            margin-left: 100px;
        }

        @media (max-width: 904px) {
            .gif-container img {
            margin-left: 30px
            }
        }
        @media (max-width: 800px) {
            .gif-container img {
                
            margin-left: 40px
            }
        }
        @media (max-width: 720px) {
            .gif-container img {
                width: 200px;
            height: 180px;
            margin-left: 20px
            }
        }

        @keyframes pop {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.2);
            }
        }

        .page {
            display: flex;
            justify-content: center;
            /* align-items: center; */   
        }

        @media (max-width: 720px) {
            .page {
                flex-direction: column;
            }
        }

        .container1 {
            display: flex;
            flex-direction: column;
            align-items: center;
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
    
    <section class="page">
        <div class="gif-container">
            <img src="https://png.pngtree.com/png-vector/20220916/ourmid/pngtree-red-location-pin-icon-design-with-gray-folded-map-png-image_6177038.png" alt="Popping GIF">
        </div>

        <div class="container1">
            <div class="container">
                <h2>Find Fortis Hospitals in Mumbai Below</h2>
                <!-- <button type="button">Locate Me</button> -->
                <a href="find_hospital.php"><button>Locate Me</button></a>
            </div>

        </div>
        
    </section>
    <div class="about">
                <h3>About Us</h3>
                <p>BookMyBed is a search engine designed to help you locate the nearest hospitals and doctors. Currently, we are focusing on listing Fortis Hospitals in Mumbai, enabling users to easily find and book beds or consult with doctors at these locations.</p>
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
