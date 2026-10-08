<?php

session_start();

require "config/database.php";

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>TravelEase - Flight Booking</title>

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


<section class="hero">

    <h1>
        Find Your Next Flight
    </h1>

    <p>
        Search, compare and book flights easily with TravelEase.
    </p>

</section>


<section class="search-box">

    <form
        class="search-form"
        id="searchForm"
        action="results.php"
        method="GET"
        novalidate
    >


        <div class="form-group">

            <label for="from">
                From
            </label>

            <input
                type="text"
                id="from"
                name="from"
                placeholder="Sydney"
            >

        </div>


        <div class="form-group">

            <label for="to">
                To
            </label>

            <input
                type="text"
                id="to"
                name="to"
                placeholder="Melbourne"
            >

        </div>


        <div class="form-group">

            <label for="date">
                Departure Date
            </label>

            <input
                type="text"
                id="date"
                name="date"
                placeholder="DD/MM/YYYY"
            >

        </div>


        <button type="submit">
            Search Flights
        </button>

    </form>


    <!-- JavaScript validation message -->

    <div
        id="searchError"
        style="
            display: none;
            margin-top: 15px;
            padding: 12px;
            background: #ffebee;
            border: 1px solid #ef9a9a;
            border-radius: 5px;
            color: #b71c1c;
        "
    >
    </div>

</section>


<section class="deals">

    <h2>
        Popular Deals
    </h2>


    <div class="deal-container">


        <!-- Sydney to Melbourne -->

        <div
            class="deal-card popular-deal"
            data-from="Sydney"
            data-to="Melbourne"
        >

            <h3>
                Sydney → Melbourne
            </h3>

            <p>
                Direct flight
            </p>

            <div class="price">
                From $139
            </div>

        </div>


        <!-- Sydney to Gold Coast -->

        <div
            class="deal-card popular-deal"
            data-from="Sydney"
            data-to="Gold Coast"
        >

            <h3>
                Sydney → Gold Coast
            </h3>

            <p>
                Direct flight
            </p>

            <div class="price">
                From $149
            </div>

        </div>


        <!-- Sydney to Singapore -->

        <div
            class="deal-card popular-deal"
            data-from="Sydney"
            data-to="Singapore"
        >

            <h3>
                Sydney → Singapore
            </h3>

            <p>
                International flight
            </p>

            <div class="price">
                From $699
            </div>

        </div>


    </div>

</section>


<script src="js/script.js"></script>


</body>

</html>