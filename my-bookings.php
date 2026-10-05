<?php

session_start();

require "config/database.php";


if (!isset($_SESSION["user_id"])) {

    header("Location: login.php");
    exit;

}


$userID = $_SESSION["user_id"];


$sql = "SELECT bookings.BookingID,
               bookings.BookingDate,
               bookings.Status,
               flights.FlightNumber,
               flights.Departure,
               flights.Destination,
               flights.DepartureDate,
               flights.DepartureTime,
               flights.ArrivalTime,
               flights.Price,
               flights.Baggage
        FROM bookings
        JOIN flights
        ON bookings.FlightID = flights.FlightID
        WHERE bookings.UserID = ?
        ORDER BY bookings.BookingDate DESC";


$stmt = $pdo->prepare($sql);

$stmt->execute([$userID]);

$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>My Bookings - TravelEase</title>

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


<section class="deals">


    <h1>
        My Bookings
    </h1>


    <p>
        Your confirmed flight bookings.
    </p>


    <?php if (count($bookings) > 0): ?>


        <?php foreach ($bookings as $booking): ?>


            <div class="deal-card"
                 style="margin-bottom: 20px;">


                <h2>

                    <?= htmlspecialchars($booking["FlightNumber"]) ?>

                </h2>


                <p>

                    <?= htmlspecialchars($booking["Departure"]) ?>

                    →

                    <?= htmlspecialchars($booking["Destination"]) ?>

                </p>


                <p>

                    <strong>Date:</strong>

                    <?= date(
                        "d/m/Y",
                        strtotime($booking["DepartureDate"])
                    ) ?>

                </p>


                <p>

                    <strong>Departure:</strong>

                    <?= htmlspecialchars($booking["DepartureTime"]) ?>

                </p>


                <p>

                    <strong>Arrival:</strong>

                    <?= htmlspecialchars($booking["ArrivalTime"]) ?>

                </p>


                <p>

                    <strong>Baggage:</strong>

                    <?= htmlspecialchars($booking["Baggage"]) ?>

                </p>


                <p>

                    <strong>Price:</strong>

                    $<?= htmlspecialchars($booking["Price"]) ?>

                </p>


                <p>

                    <strong>Status:</strong>

                    <?= htmlspecialchars($booking["Status"]) ?>

                </p>


                <p>

                    <strong>Booking ID:</strong>

                    <?= htmlspecialchars($booking["BookingID"]) ?>

                </p>


            </div>


        <?php endforeach; ?>


    <?php else: ?>


        <div class="deal-card">

            <h3>
                No bookings found.
            </h3>

            <p>
                You have not booked any flights yet.
            </p>

            <a href="index.php">
                Search Flights
            </a>

        </div>


    <?php endif; ?>


</section>


</body>

</html>