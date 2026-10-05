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


    <div class="results-heading">

        <div>

            <h1>
                Flight Results
            </h1>

            <?php if ($searched): ?>

                <p class="route-summary">

                    <?= htmlspecialchars($from) ?>

                    →

                    <?= htmlspecialchars($to) ?>

                    <span class="route-divider">
                        |
                    </span>

                    <?= htmlspecialchars($dateInput) ?>

                </p>

            <?php endif; ?>

        </div>


        <?php if ($searched): ?>

            <a href="index.php"
               class="change-search-link">

                Change Search

            </a>

        <?php endif; ?>

    </div>


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


        <?php if (count($flights) > 0): ?>


            <!-- Filter and Sort -->

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


            <!-- Flight List -->

            <div id="flightList">


                <?php foreach ($flights as $flight): ?>


                    <?php

                    $departureTimestamp =
                        strtotime($flight["DepartureTime"]);


                    $departureDisplay =
                        date(
                            "H:i",
                            strtotime($flight["DepartureTime"])
                        );


                    $arrivalDisplay =
                        date(
                            "H:i",
                            strtotime($flight["ArrivalTime"])
                        );


                    /*
                     * Calculate flight duration
                     */

                    $departureTime =
                        strtotime($flight["DepartureTime"]);

                    $arrivalTime =
                        strtotime($flight["ArrivalTime"]);


                    /*
                     * Handle flights arriving
                     * after midnight
                     */

                    if ($arrivalTime < $departureTime) {

                        $arrivalTime += 86400;

                    }


                    $durationMinutes =
                        round(
                            ($arrivalTime - $departureTime) / 60
                        );


                    $durationHours =
                        floor($durationMinutes / 60);


                    $remainingMinutes =
                        $durationMinutes % 60;


                    $durationDisplay =
                        $durationHours . "h " .
                        $remainingMinutes . "m";


                    /*
                     * Display zero stops as Direct
                     */

                    $stopsValue =
                        strtolower(
                            trim($flight["Stops"])
                        );


                    if (
                        $stopsValue === "0" ||
                        $stopsValue === "0 stops" ||
                        $stopsValue === "direct"
                    ) {

                        $stopsDisplay = "Direct";

                    } else {

                        $stopsDisplay =
                            htmlspecialchars(
                                $flight["Stops"]
                            );

                    }

                    ?>


                    <div class="flight-card"

                         data-price="<?= htmlspecialchars(
                             $flight["Price"]
                         ) ?>"

                         data-departure="<?= $departureTimestamp ?>"

                         data-stops="<?= htmlspecialchars(
                             $flight["Stops"]
                         ) ?>">


                        <!-- Flight Number -->

                        <div class="flight-number">

                            <span class="small-label">
                                Flight
                            </span>

                            <strong>
                                <?= htmlspecialchars(
                                    $flight["FlightNumber"]
                                ) ?>
                            </strong>

                        </div>


                        <!-- Departure -->

                        <div class="flight-location">

                            <span class="city-name">

                                <?= htmlspecialchars(
                                    $flight["Departure"]
                                ) ?>

                            </span>

                            <span class="location-time">

                                <?= $departureDisplay ?>

                            </span>

                        </div>


                        <!-- Route -->

                        <div class="flight-route">

                            <span class="duration">

                                <?= $durationDisplay ?>

                            </span>


                            <div class="route-line">

                                <span class="route-dot"></span>

                                <span class="route-track"></span>

                                <span class="route-arrow">
                                    ›
                                </span>

                            </div>


                            <span class="stops-text">

                                <?= $stopsDisplay ?>

                            </span>

                        </div>


                        <!-- Arrival -->

                        <div class="flight-location">

                            <span class="city-name">

                                <?= htmlspecialchars(
                                    $flight["Destination"]
                                ) ?>

                            </span>

                            <span class="location-time">

                                <?= $arrivalDisplay ?>

                            </span>

                        </div>


                        <!-- Baggage -->

                        <div class="baggage-info">

                            <span class="small-label">
                                Baggage
                            </span>

                            <strong>

                                <?= htmlspecialchars(
                                    $flight["Baggage"]
                                ) ?>

                            </strong>

                        </div>


                        <!-- Seats -->

                        <div class="seat-info">

                            <span class="small-label">
                                Seats
                            </span>

                            <strong>

                                <?= htmlspecialchars(
                                    $flight["AvailableSeats"]
                                ) ?>

                            </strong>

                        </div>


                        <!-- Price -->

                        <div class="flight-price">

                            <span class="small-label">
                                From
                            </span>

                            <strong>

                                $<?= number_format(
                                    (float) $flight["Price"],
                                    2
                                ) ?>

                            </strong>

                        </div>


                        <!-- Booking -->

                        <div class="flight-action">

                            <a href="booking.php?flight_id=<?= $flight["FlightID"] ?>">

                                <button type="button">

                                    Book Now

                                </button>

                            </a>

                        </div>


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