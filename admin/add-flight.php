<?php

session_start();

require "../config/database.php";


// Only admin can access this page
if (!isset($_SESSION["user_id"])) {
    header("Location: ../login.php");
    exit;
}

if ($_SESSION["user_role"] != "admin") {
    header("Location: ../index.php");
    exit;
}


$message = "";


// Add flight
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $flightNumber = trim($_POST["flight_number"]);
    $departure = trim($_POST["departure"]);
    $destination = trim($_POST["destination"]);

    $departureDateInput = trim($_POST["departure_date"]);

    $departureTime = $_POST["departure_time"];
    $arrivalTime = $_POST["arrival_time"];
    $price = $_POST["price"];
    $stops = $_POST["stops"];
    $baggage = trim($_POST["baggage"]);
    $availableSeats = $_POST["available_seats"];


    // Convert DD/MM/YYYY to YYYY-MM-DD
    $dateObject = DateTime::createFromFormat(
        "d/m/Y",
        $departureDateInput
    );

    $departureDate = "";

    if ($dateObject) {
        $departureDate = $dateObject->format("Y-m-d");
    }


    if (
        $flightNumber == "" ||
        $departure == "" ||
        $destination == "" ||
        $departureDate == "" ||
        $departureTime == "" ||
        $arrivalTime == "" ||
        $price == "" ||
        $availableSeats == ""
    ) {

        $message = "Please complete all required fields.";

    } elseif ($departure == $destination) {

        $message = "Departure and destination cannot be the same.";

    } else {

        $sql = "INSERT INTO flights
                (FlightNumber, Departure, Destination, DepartureDate,
                 DepartureTime, ArrivalTime, Price, Stops, Baggage, AvailableSeats)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            $flightNumber,
            $departure,
            $destination,
            $departureDate,
            $departureTime,
            $arrivalTime,
            $price,
            $stops,
            $baggage,
            $availableSeats
        ]);

        header("Location: index.php");
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Add Flight - TravelEase Admin</title>

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
        Add New Flight
    </h1>

    <p>
        Create a new flight for TravelEase.
    </p>


    <?php if ($message != ""): ?>

        <p>
            <strong>
                <?= htmlspecialchars($message) ?>
            </strong>
        </p>

    <?php endif; ?>


    <form method="POST"
          action="add-flight.php">


        <div class="form-group">

            <label>
                Flight Number
            </label>

            <input
                type="text"
                name="flight_number"
                placeholder="e.g. QF402"
                required
            >

        </div>


        <br>


        <div class="form-group">

            <label>
                Departure
            </label>

            <input
                type="text"
                name="departure"
                placeholder="e.g. Sydney"
                required
            >

        </div>


        <br>


        <div class="form-group">

            <label>
                Destination
            </label>

            <input
                type="text"
                name="destination"
                placeholder="e.g. Melbourne"
                required
            >

        </div>


        <br>


        <div class="form-group">

            <label>
                Departure Date
            </label>

            <div style="position: relative;">

                <input
                    type="text"
                    id="departure_date"
                    name="departure_date"
                    placeholder="DD/MM/YYYY"
                    maxlength="10"
                    required
                >

                <input
                    type="date"
                    id="calendar_date"
                    lang="en-AU"
                    style="
                        position: absolute;
                        right: 10px;
                        top: 50%;
                        transform: translateY(-50%);
                        width: 35px;
                        height: 30px;
                        opacity: 0;
                        cursor: pointer;
                    "
                >

                <span style="
                    position: absolute;
                    right: 16px;
                    top: 50%;
                    transform: translateY(-50%);
                    pointer-events: none;
                    font-size: 18px;
                ">
                    📅
                </span>

            </div>

        </div>


        <br>


        <div class="form-group">

            <label>
                Departure Time
            </label>

            <input
                type="time"
                name="departure_time"
                required
            >

        </div>


        <br>


        <div class="form-group">

            <label>
                Arrival Time
            </label>

            <input
                type="time"
                name="arrival_time"
                required
            >

        </div>


        <br>


        <div class="form-group">

            <label>
                Price ($)
            </label>

            <input
                type="number"
                name="price"
                step="0.01"
                min="0"
                placeholder="e.g. 189.00"
                required
            >

        </div>


        <br>


        <div class="form-group">

            <label>
                Stops
            </label>

            <input
                type="number"
                name="stops"
                min="0"
                value="0"
                required
            >

        </div>


        <br>


        <div class="form-group">

            <label>
                Baggage
            </label>

            <input
                type="text"
                name="baggage"
                placeholder="e.g. 23kg"
            >

        </div>


        <br>


        <div class="form-group">

            <label>
                Available Seats
            </label>

            <input
                type="number"
                name="available_seats"
                min="0"
                placeholder="e.g. 80"
                required
            >

        </div>


        <br>


        <button type="submit">
            Add Flight
        </button>


    </form>


    <p>
        <a href="index.php">
            Back to Admin Dashboard
        </a>
    </p>


</section>


<script>

const calendarDate = document.getElementById("calendar_date");
const departureDate = document.getElementById("departure_date");


calendarDate.addEventListener("change", function () {

    if (this.value == "") {
        return;
    }

    const parts = this.value.split("-");

    const year = parts[0];
    const month = parts[1];
    const day = parts[2];

    departureDate.value =
        day + "/" + month + "/" + year;

});

</script>


</body>

</html>