<?php

session_start();

require "../config/database.php";


if (!isset($_SESSION["user_id"])) {

    header("Location: ../login.php");
    exit;

}


if ($_SESSION["user_role"] != "admin") {

    header("Location: ../index.php");
    exit;

}


$sql = "SELECT * FROM flights
        ORDER BY DepartureDate ASC,
        DepartureTime ASC";

$stmt = $pdo->query($sql);

$flights = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Admin Dashboard - TravelEase</title>

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


<section class="deals">

    <h1>
        Admin Dashboard
    </h1>

    <p>
        Manage TravelEase flights.
    </p>


    <p>

        <a href="add-flight.php">

            <button type="button">
                Add New Flight
            </button>

        </a>

    </p>


    <?php foreach ($flights as $flight): ?>


        <div class="deal-card"
             style="margin-bottom: 20px;">


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


            <a href="edit-flight.php?id=<?= $flight["FlightID"] ?>">

                <button type="button">
                    Edit
                </button>

            </a>


            <a href="delete-flight.php?id=<?= $flight["FlightID"] ?>">

                <button type="button">
                    Delete
                </button>

            </a>


        </div>


    <?php endforeach; ?>


</section>


</body>

</html>