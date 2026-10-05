<?php

require "config/database.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    if ($name == "" || $email == "" || $password == "") {

        $message = "Please complete all fields.";

    } else {

        $check = $pdo->prepare(
            "SELECT UserID FROM users WHERE Email = ?"
        );

        $check->execute([$email]);

        if ($check->fetch()) {

            $message = "This email is already registered.";

        } else {

            $hashedPassword = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            $sql = "INSERT INTO users
                    (Name, Email, Password, Role)
                    VALUES (?, ?, ?, 'customer')";

            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                $name,
                $email,
                $hashedPassword
            ]);

            $message = "Registration successful!";
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

    <title>Register - TravelEase</title>

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

    <h1>Create Account</h1>

    <p>
        Register to book and manage your flights.
    </p>


    <?php if ($message != ""): ?>

        <p>
            <strong>
                <?= htmlspecialchars($message) ?>
            </strong>
        </p>

    <?php endif; ?>


    <form method="POST"
          action="register.php">


        <div class="form-group">

            <label for="name">
                Full Name
            </label>

            <input
                type="text"
                id="name"
                name="name"
                required
            >

        </div>


        <br>


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
            Register
        </button>


    </form>


    <p>
        Already have an account?
        <a href="login.php">Login</a>
    </p>

</section>


</body>

</html>