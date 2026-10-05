function validateSearch() {

    const from = document.getElementById("from").value.trim();
    const to = document.getElementById("to").value.trim();
    const date = document.getElementById("date").value.trim();

    if (from.toLowerCase() === to.toLowerCase()) {
        alert("Departure and destination cannot be the same.");
        return false;
    }

    const pattern = /^\d{2}\/\d{2}\/\d{4}$/;

    if (!pattern.test(date)) {
        alert("Please enter the date as DD/MM/YYYY.");
        return false;
    }

    return true;
}


/*
 * Flight Filter and Sort
 *
 * This function runs after the HTML page has loaded.
 * It allows users to filter and sort flight results
 * without refreshing the page.
 */

document.addEventListener("DOMContentLoaded", function () {

    const sortSelect = document.getElementById("sortFlights");
    const stopsSelect = document.getElementById("filterStops");
    const flightList = document.getElementById("flightList");
    const flightCount = document.getElementById("flightCount");
    const noFilterResults = document.getElementById("noFilterResults");


    /*
     * The filter controls only exist on results.php.
     * If the user is on another page, stop here.
     */

    if (!sortSelect || !stopsSelect || !flightList) {
        return;
    }


    function updateFlights() {

        const sortValue = sortSelect.value;
        const stopsValue = stopsSelect.value;

        const flights = Array.from(
            flightList.querySelectorAll(".flight-card")
        );


        /*
         * FILTER
         *
         * Read the number of stops from each
         * flight card's data-stops attribute.
         */

        const visibleFlights = flights.filter(function (flight) {

            const stops = flight.dataset.stops.toLowerCase();

            if (stopsValue === "all") {
                return true;
            }

            if (stopsValue === "direct") {

                return stops === "direct" ||
                       stops === "0" ||
                       stops === "0 stops";

            }

            if (stopsValue === "1") {

                return stops === "1" ||
                       stops === "1 stop";

            }

            return true;

        });


        /*
         * SORT
         *
         * Convert price and departure values
         * into numbers before comparing them.
         */

        visibleFlights.sort(function (a, b) {

            const priceA = parseFloat(a.dataset.price);
            const priceB = parseFloat(b.dataset.price);

            const departureA = parseInt(a.dataset.departure);
            const departureB = parseInt(b.dataset.departure);


            if (sortValue === "price-low") {
                return priceA - priceB;
            }

            if (sortValue === "price-high") {
                return priceB - priceA;
            }

            if (sortValue === "departure-early") {
                return departureA - departureB;
            }

            if (sortValue === "departure-late") {
                return departureB - departureA;
            }

            return 0;

        });


        /*
         * Hide all flight cards.
         */

        flights.forEach(function (flight) {

            flight.style.display = "none";

        });


        /*
         * Display the filtered flights
         * in their new sorted order.
         */

        visibleFlights.forEach(function (flight) {

            flight.style.display = "block";

            flightList.appendChild(flight);

        });


        /*
         * Update result counter.
         */

        flightCount.textContent =
            visibleFlights.length +
            (visibleFlights.length === 1
                ? " flight found"
                : " flights found");


        /*
         * Show a message if the filter
         * produces no results.
         */

        if (visibleFlights.length === 0) {

            noFilterResults.style.display = "block";

        } else {

            noFilterResults.style.display = "none";

        }

    }


    /*
     * Run the algorithm whenever the
     * user changes a filter option.
     */

    sortSelect.addEventListener("change", updateFlights);

    stopsSelect.addEventListener("change", updateFlights);

});