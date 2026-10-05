<?php

session_start();

require "config/database.php";

$from = $_GET["from"] ?? "";
$to = $_GET["to"] ?? "";
$dateInput = $_GET["date"] ?? "";

$flights = [];
$searched = false;


if ($from != "" && $to != "" && $dateInput != "") {

    $dateObject = DateTime::createFromFormat(
        "d/m/Y",
        $dateInput
    );


    if ($dateObject) {

        $date = $dateObject->format("Y-m-d");


        $sql = "SELECT * FROM flights
                WHERE Departure = ?
                AND Destination = ?
                AND DepartureDate = ?
                ORDER BY Price ASC";


        $stmt = $pdo->prepare($sql);


        $stmt->execute([
            $from,
            $to,
            $date
        ]);


        $flights = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $searched = true;

    }

}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Flight Results - TravelEase</title>

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


        <?php if (isset($_SESSION["user_id"])): ?>

            <span>

                Welcome,
                <?= htmlspecialchars($_SESSION["user_name"]) ?>

            </span>

            <a href="logout.php">
                Logout
            </a>


        <?php else: ?>

            <a href="login.php">
                Login
            </a>

            <a href="register.php">
                Register
            </a>

        <?php endif; ?>


    </nav>

</header>


<section class="deals">


    <h1>
        Flight Results
    </h1>


    <?php if (!$searched): ?>


        <div class="deal-card">

            <h3>
                No search has been made.
            </h3>

            <p>
                Please return to the home page and search for a flight.
            </p>

            <a href="index.php">
                Back to Search
            </a>

        </div>


    <?php else: ?>


        <p>

            <?= htmlspecialchars($from) ?>

            →

            <?= htmlspecialchars($to) ?>

            |

            <?= htmlspecialchars($dateInput) ?>

        </p>


        <?php if (count($flights) > 0): ?>


            <!-- Filter and Sort Controls -->

            <div class="filter-box">


                <div class="form-group">

                    <label for="sortFlights">
                        Sort by
                    </label>

                    <select id="sortFlights">

                        <option value="price-low">
                            Price: Low to High
                        </option>

                        <option value="price-high">
                            Price: High to Low
                        </option>

                        <option value="departure-early">
                            Departure: Earliest
                        </option>

                        <option value="departure-late">
                            Departure: Latest
                        </option>

                    </select>

                </div>


                <div class="form-group">

                    <label for="filterStops">
                        Stops
                    </label>

                    <select id="filterStops">

                        <option value="all">
                            All Flights
                        </option>

                        <option value="direct">
                            Direct Only
                        </option>

                        <option value="1">
                            1 Stop
                        </option>

                    </select>

                </div>


            </div>


            <p id="flightCount">

                <?= count($flights) ?> flights found

            </p>


            <!-- Flight Cards -->

            <div id="flightList">


                <?php foreach ($flights as $flight): ?>


                    <?php

                    $departureTimestamp =
                        strtotime($flight["DepartureTime"]);

                    ?>


                    <div class="deal-card flight-card"

                         data-price="<?= htmlspecialchars($flight["Price"]) ?>"

                         data-departure="<?= $departureTimestamp ?>"

                         data-stops="<?= htmlspecialchars($flight["Stops"]) ?>">


                        <h2>
                            <?= htmlspecialchars($flight["FlightNumber"]) ?>
                        </h2>


                        <p>

                            <?= htmlspecialchars($flight["Departure"]) ?>

                            →

                            <?= htmlspecialchars($flight["Destination"]) ?>

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

                            <strong>Stops:</strong>

                            <?= htmlspecialchars($flight["Stops"]) ?>

                        </p>


                        <p>

                            <strong>Baggage:</strong>

                            <?= htmlspecialchars($flight["Baggage"]) ?>

                        </p>


                        <p>

                            <strong>Available Seats:</strong>

                            <?= htmlspecialchars($flight["AvailableSeats"]) ?>

                        </p>


                        <div class="price">

                            $<?= htmlspecialchars($flight["Price"]) ?>

                        </div>


                        <br>


                        <a href="booking.php?flight_id=<?= $flight["FlightID"] ?>">

                            <button type="button">
                                Book Now
                            </button>

                        </a>


                    </div>


                <?php endforeach; ?>


            </div>


            <div id="noFilterResults"
                 class="deal-card"
                 style="display: none;">

                <h3>
                    No flights match your filters.
                </h3>

                <p>
                    Please change the filter options and try again.
                </p>

            </div>


        <?php else: ?>


            <div class="deal-card">

                <h3>
                    No flights found.
                </h3>

                <p>
                    Please try another destination or date.
                </p>

                <a href="index.php">
                    Back to Search
                </a>

            </div>


        <?php endif; ?>


    <?php endif; ?>


</section>


<script src="js/script.js"></script>


</body>

</html>