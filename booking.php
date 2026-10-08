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

    try {

        // Start database transaction
        $pdo->beginTransaction();

        // Check whether the user has already booked this flight
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

            // Nothing has been changed
            $pdo->rollBack();

        } else {

            // Reduce available seats by 1
            $updateSeats = $pdo->prepare(
                "UPDATE flights
                 SET AvailableSeats = AvailableSeats - 1
                 WHERE FlightID = ?
                 AND AvailableSeats > 0"
            );

            $updateSeats->execute([
                $flightID
            ]);

            // Check whether a seat was actually available
            if ($updateSeats->rowCount() === 0) {

                $message = "Sorry, this flight is fully booked.";

                $pdo->rollBack();

            } else {

                // Create the booking
                $sql = "INSERT INTO bookings
                        (UserID, FlightID, Status)
                        VALUES (?, ?, 'Confirmed')";

                $stmt = $pdo->prepare($sql);

                $stmt->execute([
                    $userID,
                    $flightID
                ]);

                // Save all changes
                $pdo->commit();

                $message = "Booking successful!";

                // Update the displayed flight information
                $flight["AvailableSeats"]--;
            }
        }

    } catch (PDOException $e) {

        // Undo database changes if something goes wrong
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }

        $message = "Booking failed. Please try again later.";
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
            
            $<?= number_format((float)$flight["Price"], 2) ?>

        </p>


        <div style="
            background-color: #f0f7ff;
            border: 1px solid #cfe3f8;
            padding: 15px;
            margin-top: 15px;
            border-radius: 8px;
        ">
            <strong>🎓 Student Discount Reminder</strong>

        <p style="margin: 8px 0 0 0;">
             Eligible students may be able to receive a student discount.
             Please check eligibility before booking.
        </p>

        </div>
    
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