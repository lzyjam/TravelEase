<?php

session_start();

require "../config/database.php";


// Only admin can access
if (!isset($_SESSION["user_id"])) {

    header("Location: ../login.php");
    exit;

}

if ($_SESSION["user_role"] != "admin") {

    header("Location: ../index.php");
    exit;

}


// Get Flight ID
if (!isset($_GET["id"])) {

    header("Location: index.php");
    exit;

}

$flightID = $_GET["id"];

$message = "";


// Get flight information
$sql = "SELECT * FROM flights WHERE FlightID = ?";

$stmt = $pdo->prepare($sql);

$stmt->execute([$flightID]);

$flight = $stmt->fetch(PDO::FETCH_ASSOC);


if (!$flight) {

    header("Location: index.php");
    exit;

}


// Update flight
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $flightNumber = trim($_POST["flight_number"]);
    $departure = trim($_POST["departure"]);
    $destination = trim($_POST["destination"]);

    $displayDate = trim($_POST["departure_date"]);

    $departureTime = $_POST["departure_time"];
    $arrivalTime = $_POST["arrival_time"];

    $price = $_POST["price"];
    $stops = $_POST["stops"];
    $baggage = trim($_POST["baggage"]);
    $availableSeats = $_POST["available_seats"];


    // Convert Australian date DD/MM/YYYY to MySQL YYYY-MM-DD
    $dateObject = DateTime::createFromFormat(
        "d/m/Y",
        $displayDate
    );


    if (!$dateObject) {

        $message = "Please enter the date as DD/MM/YYYY.";

    } elseif ($departure == $destination) {

        $message = "Departure and destination cannot be the same.";

    } else {

        $departureDate = $dateObject->format("Y-m-d");


        $sql = "UPDATE flights
                SET FlightNumber = ?,
                    Departure = ?,
                    Destination = ?,
                    DepartureDate = ?,
                    DepartureTime = ?,
                    ArrivalTime = ?,
                    Price = ?,
                    Stops = ?,
                    Baggage = ?,
                    AvailableSeats = ?
                WHERE FlightID = ?";


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
            $availableSeats,
            $flightID

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

    <title>Edit Flight - TravelEase Admin</title>

    <link rel="stylesheet"
          href="../css/style.css">


    <style>

        .date-wrapper {

            position: relative;

        }


        .date-wrapper input {

            width: 100%;
            box-sizing: border-box;
            padding-right: 55px;

        }


        .calendar-button {

            position: absolute;

            right: 12px;
            top: 50%;

            transform: translateY(-50%);

            border: none;
            background: none;

            font-size: 22px;

            cursor: pointer;

        }


        #realDate {

            position: absolute;

            opacity: 0;

            width: 1px;
            height: 1px;

            pointer-events: none;

        }

    </style>

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
         style="margin-top: 50px; max-width: 600px;">


    <h1>

        Edit Flight

    </h1>


    <p>

        Update flight information.

    </p>


    <?php if ($message != ""): ?>

        <p>

            <strong>
                <?= htmlspecialchars($message) ?>
            </strong>

        </p>

    <?php endif; ?>



    <form method="POST">


        <div class="form-group">

            <label>
                Flight Number
            </label>

            <input
                type="text"
                name="flight_number"
                value="<?= htmlspecialchars($flight["FlightNumber"]) ?>"
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
                value="<?= htmlspecialchars($flight["Departure"]) ?>"
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
                value="<?= htmlspecialchars($flight["Destination"]) ?>"
                required
            >

        </div>


        <br>


        <div class="form-group">

            <label>
                Departure Date
            </label>


            <div class="date-wrapper">


                <input
                    type="text"
                    id="displayDate"
                    name="departure_date"
                    value="<?= date("d/m/Y", strtotime($flight["DepartureDate"])) ?>"
                    placeholder="DD/MM/YYYY"
                    required
                >


                <button
                    type="button"
                    class="calendar-button"
                    onclick="openCalendar()"
                >
                    📅
                </button>


                <input
                    type="date"
                    id="realDate"
                    lang="en-AU"
                    value="<?= htmlspecialchars($flight["DepartureDate"]) ?>"
                >


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
                value="<?= substr($flight["DepartureTime"], 0, 5) ?>"
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
                value="<?= substr($flight["ArrivalTime"], 0, 5) ?>"
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
                value="<?= htmlspecialchars($flight["Price"]) ?>"
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
                value="<?= htmlspecialchars($flight["Stops"]) ?>"
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
                value="<?= htmlspecialchars($flight["Baggage"]) ?>"
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
                value="<?= htmlspecialchars($flight["AvailableSeats"]) ?>"
                required
            >

        </div>


        <br>


        <button type="submit">

            Save Changes

        </button>


    </form>


    <p>

        <a href="index.php">

            Back to Admin Dashboard

        </a>

    </p>


</section>



<script>

function openCalendar() {

    const calendar = document.getElementById("realDate");

    if (calendar.showPicker) {

        calendar.showPicker();

    } else {

        calendar.click();

    }

}


document
    .getElementById("realDate")
    .addEventListener("change", function () {

        if (!this.value) {

            return;

        }

        const parts = this.value.split("-");

        const year = parts[0];
        const month = parts[1];
        const day = parts[2];

        document.getElementById("displayDate").value =
            day + "/" + month + "/" + year;

    });

</script>


</body>

</html>