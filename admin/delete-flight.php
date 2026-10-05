<?php

session_start();

require "../config/database.php";


// Only logged-in users can access
if (!isset($_SESSION["user_id"])) {

    header("Location: ../login.php");
    exit;

}


// Only admin can access
if ($_SESSION["user_role"] != "admin") {

    header("Location: ../index.php");
    exit;

}


// Check Flight ID
if (!isset($_GET["id"])) {

    header("Location: index.php");
    exit;

}


$flightID = $_GET["id"];


// Get flight information
$sql = "SELECT * FROM flights WHERE FlightID = ?";

$stmt = $pdo->prepare($sql);

$stmt->execute([$flightID]);

$flight = $stmt->fetch(PDO::FETCH_ASSOC);


// Flight does not exist
if (!$flight) {

    header("Location: index.php");
    exit;

}


// Delete flight after confirmation
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $sql = "DELETE FROM flights WHERE FlightID = ?";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([$flightID]);


    header("Location: index.php");
    exit;

}

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Delete Flight - TravelEase Admin</title>

    <link rel="stylesheet"
          href="../css/style.css">

</head>


<body>


<header>

    <div class="logo">

        TravelEase Admin

    </div>


    <nav>

        <a href="../index.php">
            Customer Site
        </a>

        <a href="index.php">
            Flights
        </a>

        <a href="add-flight.php">
            Add Flight
        </a>

        <span>

            Welcome,
            <?= htmlspecialchars($_SESSION["user_name"]) ?>

        </span>

        <a href="../logout.php">
            Logout
        </a>

    </nav>

</header>



<section class="search-box"
         style="margin-top: 60px; max-width: 600px;">


    <h1>

        Delete Flight

    </h1>


    <p>

        Are you sure you want to delete this flight?

    </p>



    <div class="deal-card"
         style="margin-top: 25px;">


        <h2>

            <?= htmlspecialchars($flight["FlightNumber"]) ?>

        </h2>


        <p>

            <?= htmlspecialchars($flight["Departure"]) ?>

            →

            <?= htmlspecialchars($flight["Destination"]) ?>

        </p>


        <p>

            <strong>Date:</strong>

            <?= date(
                "d/m/Y",
                strtotime($flight["DepartureDate"])
            ) ?>

        </p>


        <p>

            <strong>Departure:</strong>

            <?= htmlspecialchars($flight["DepartureTime"]) ?>

        </p>


        <p>

            <strong>Arrival:</strong>

            <?= htmlspecialchars($flight["ArrivalTime"]) ?>

        </p>


        <p>

            <strong>Price:</strong>

            $<?= htmlspecialchars($flight["Price"]) ?>

        </p>


        <p>

            <strong>Available Seats:</strong>

            <?= htmlspecialchars($flight["AvailableSeats"]) ?>

        </p>


    </div>



    <br>


    <form method="POST">

        <button type="submit">

            Confirm Delete

        </button>

    </form>


    <br>


    <a href="index.php">

        Cancel

    </a>


</section>


</body>

</html>