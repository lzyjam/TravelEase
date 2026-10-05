<?php

session_start();

require "config/database.php";

$message = "";


// If login form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST["email"]);
    $password = $_POST["password"];


    // Check empty fields
    if ($email == "" || $password == "") {

        $message = "Please complete all fields.";

    } else {

        // Find user by email
        $sql = "SELECT * FROM users WHERE Email = ?";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([$email]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);


        // Check password
        if ($user && password_verify($password, $user["Password"])) {

            // Save user information in session
            $_SESSION["user_id"] = $user["UserID"];

            $_SESSION["user_name"] = $user["Name"];

            $_SESSION["user_role"] = $user["Role"];


            // Admin goes to Admin Dashboard
            if ($user["Role"] == "admin") {

                header("Location: admin/index.php");

                exit;

            }


            // Customer goes to homepage
            header("Location: index.php");

            exit;


        } else {

            $message = "Incorrect email or password.";

        }
    }
}

?>


<!DOCTYPE html>

<html lang="en">


<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Login - TravelEase</title>

    <link rel="stylesheet"
          href="css/style.css">

</head>


<body>


<header>

    <div class="logo">

        TravelEase

    </div>


    <nav>

        <a href="index.php">
            Home
        </a>

        <a href="index.php">
            Search Flights
        </a>

        <a href="my-bookings.php">
            My Bookings
        </a>

        <a href="login.php">
            Login
        </a>

    </nav>

</header>



<section class="search-box"
         style="margin-top: 60px; max-width: 500px;">


    <h1>
        Login
    </h1>


    <p>
        Login to book and manage your flights.
    </p>


    <?php if ($message != ""): ?>

        <p>

            <strong>

                <?= htmlspecialchars($message) ?>

            </strong>

        </p>

    <?php endif; ?>



    <form method="POST"
          action="login.php">


        <div class="form-group">

            <label for="email">

                Email

            </label>


            <input
                type="email"
                id="email"
                name="email"
                required
            >

        </div>


        <br>


        <div class="form-group">

            <label for="password">

                Password

            </label>


            <input
                type="password"
                id="password"
                name="password"
                required
            >

        </div>


        <br>


        <button type="submit">

            Login

        </button>


    </form>


    <p>

        Don't have an account?

        <a href="register.php">
            Register
        </a>

    </p>


</section>


</body>

</html>