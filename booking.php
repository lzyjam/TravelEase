<?php

session_start();

require "config/database.php";


if (!isset($_SESSION["user_id"])) {

    header("Location: login.php");
    exit;

}


$flightID = $_GET["flight_id"] ?? "";


if ($flightID == "") {

    header("Location: index.php");
    exit;

}


$sql = "SELECT * FROM flights WHERE FlightID = ?";

$stmt = $pdo->prepare($sql);

$stmt->execute([$flightID]);

$flight = $stmt->fetch(PDO::FETCH_ASSOC);


if (!$flight) {

    header("Location: index.php");
    exit;

}


$message = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $userID = $_SESSION["user_id"];

    $check = $pdo->prepare(
        "SELECT BookingID
         FROM bookings
         WHERE UserID = ?
         AND FlightID = ?"
    );

    $check->execute([
        $userID,
        $flightID
    ]);


    if ($check->fetch()) {

        $message = "You have already booked this flight.";

    } else {

        $sql = "INSERT INTO bookings
                (UserID, FlightID, Status)
                VALUES (?, ?, 'Confirmed')";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            $userID,
            $flightID
        ]);

        $message = "Booking successful!";

    }

}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Book Flight - TravelEase</title>

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

        <span>
            Welcome,
            <?= htmlspecialchars($_SESSION["user_name"]) ?>
        </span>

        <a href="logout.php">
            Logout
        </a>

    </nav>

</header>


<section class="search-box"
         style="margin-top: 60px; max-width: 600px;">


    <h1>
        Confirm Booking
    </h1>


    <?php if ($message != ""): ?>

        <h3>
            <?= htmlspecialchars($message) ?>
        </h3>

    <?php endif; ?>


    <div class="deal-card">


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

            <strong>Baggage:</strong>

            <?= htmlspecialchars($flight["Baggage"]) ?>

        </p>


        <p>

            <strong>Price:</strong>

            $<?= htmlspecialchars($flight["Price"]) ?>

        </p>


        <?php if ($message == ""): ?>

            <form method="POST">

                <button type="submit">
                    Confirm Booking
                </button>

            </form>

        <?php endif; ?>


    </div>


    <p>

        <a href="my-bookings.php">
            View My Bookings
        </a>

    </p>


</section>


</body>

</html>