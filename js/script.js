/*
 * =========================================
 * TravelEase - Client-side JavaScript
 * =========================================
 */


/*
 * =========================================
 * Flight Search Validation
 * =========================================
 */

document.addEventListener("DOMContentLoaded", function () {

    const searchForm =
        document.getElementById("searchForm");


    if (searchForm) {

        searchForm.addEventListener(
            "submit",
            function (event) {

                const from =
                    document
                        .getElementById("from")
                        .value
                        .trim();


                const to =
                    document
                        .getElementById("to")
                        .value
                        .trim();


                const date =
                    document
                        .getElementById("date")
                        .value
                        .trim();


                const errorBox =
                    document.getElementById("searchError");


                let errorMessage = "";


                /*
                 * Check empty fields
                 */

                if (
                    from === "" ||
                    to === "" ||
                    date === ""
                ) {

                    errorMessage =
                        "Please complete all search fields.";

                }


                /*
                 * Departure and destination
                 * cannot be the same
                 */

                else if (
                    from.toLowerCase() ===
                    to.toLowerCase()
                ) {

                    errorMessage =
                        "Departure and destination cannot be the same.";

                }


                /*
                 * Check DD/MM/YYYY format
                 */

                else if (
                    !/^\d{2}\/\d{2}\/\d{4}$/.test(date)
                ) {

                    errorMessage =
                        "Please enter the date as DD/MM/YYYY.";

                }


                else {

                    const parts =
                        date.split("/");


                    const day =
                        parseInt(parts[0]);


                    const month =
                        parseInt(parts[1]);


                    const year =
                        parseInt(parts[2]);


                    /*
                     * JavaScript months start at 0
                     */

                    const selectedDate =
                        new Date(
                            year,
                            month - 1,
                            day
                        );


                    /*
                     * Check whether the date
                     * actually exists
                     */

                    const validDate =
                        selectedDate.getFullYear() === year &&
                        selectedDate.getMonth() === month - 1 &&
                        selectedDate.getDate() === day;


                    if (!validDate) {

                        errorMessage =
                            "Please enter a valid departure date.";

                    }

                    else {

                        const today =
                            new Date();


                        /*
                         * Compare calendar dates only
                         */

                        today.setHours(
                            0,
                            0,
                            0,
                            0
                        );


                        selectedDate.setHours(
                            0,
                            0,
                            0,
                            0
                        );


                        /*
                         * Reject past dates
                         */

                        if (
                            selectedDate < today
                        ) {

                            errorMessage =
                                "Departure date cannot be in the past.";

                        }

                    }

                }


                /*
                 * Stop submission if
                 * validation fails
                 */

                if (errorMessage !== "") {

                    event.preventDefault();

                    errorBox.textContent =
                        errorMessage;

                    errorBox.style.display =
                        "block";

                    return;

                }


                /*
                 * Validation successful
                 */

                errorBox.textContent = "";

                errorBox.style.display =
                    "none";

            }
        );

    }

});


/*
 * =========================================
 * Popular Deals Interaction
 * =========================================
 */

document.addEventListener("DOMContentLoaded", function () {

    const popularDeals =
        document.querySelectorAll(
            ".popular-deal"
        );


    const fromInput =
        document.getElementById("from");


    const toInput =
        document.getElementById("to");


    const dateInput =
        document.getElementById("date");


    const errorBox =
        document.getElementById("searchError");


    /*
     * Popular deals only exist
     * on the home page
     */

    if (
        popularDeals.length === 0 ||
        !fromInput ||
        !toInput ||
        !dateInput
    ) {

        return;

    }


    popularDeals.forEach(
        function (deal) {

            /*
             * Make the card keyboard accessible
             */

            deal.setAttribute(
                "tabindex",
                "0"
            );


            deal.setAttribute(
                "role",
                "button"
            );


            /*
             * Fill search form
             */

            function selectDeal() {

                const departure =
                    deal.dataset.from;


                const destination =
                    deal.dataset.to;


                fromInput.value =
                    departure;


                toInput.value =
                    destination;


                /*
                 * Remove previous
                 * validation message
                 */

                if (errorBox) {

                    errorBox.textContent = "";

                    errorBox.style.display =
                        "none";

                }


                /*
                 * Move the user directly
                 * to the date field
                 */

                dateInput.focus();


                /*
                 * Smoothly move search form
                 * into view if necessary
                 */

                document
                    .querySelector(".search-box")
                    .scrollIntoView({
                        behavior: "smooth",
                        block: "center"
                    });

            }


            /*
             * Mouse interaction
             */

            deal.addEventListener(
                "click",
                selectDeal
            );


            /*
             * Keyboard interaction
             *
             * Enter or Space can also
             * select the route
             */

            deal.addEventListener(
                "keydown",
                function (event) {

                    if (
                        event.key === "Enter" ||
                        event.key === " "
                    ) {

                        event.preventDefault();

                        selectDeal();

                    }

                }
            );

        }
    );

});


/*
 * =========================================
 * Flight Filter and Sort
 * =========================================
 */

document.addEventListener("DOMContentLoaded", function () {

    const sortSelect =
        document.getElementById("sortFlights");


    const stopsSelect =
        document.getElementById("filterStops");


    const flightList =
        document.getElementById("flightList");


    const flightCount =
        document.getElementById("flightCount");


    const noFilterResults =
        document.getElementById("noFilterResults");


    /*
     * Filter controls only exist
     * on results.php
     */

    if (
        !sortSelect ||
        !stopsSelect ||
        !flightList
    ) {

        return;

    }


    function updateFlights() {

        const sortValue =
            sortSelect.value;


        const stopsValue =
            stopsSelect.value;


        const flights =
            Array.from(
                flightList.querySelectorAll(
                    ".flight-card"
                )
            );


        /*
         * FILTER ALGORITHM
         */

        const visibleFlights =
            flights.filter(
                function (flight) {

                    const stops =
                        flight.dataset.stops
                            .toLowerCase();


                    if (
                        stopsValue === "all"
                    ) {

                        return true;

                    }


                    if (
                        stopsValue === "direct"
                    ) {

                        return (
                            stops === "direct" ||
                            stops === "0" ||
                            stops === "0 stops"
                        );

                    }


                    if (
                        stopsValue === "1"
                    ) {

                        return (
                            stops === "1" ||
                            stops === "1 stop"
                        );

                    }


                    return true;

                }
            );


        /*
         * SORT ALGORITHM
         */

        visibleFlights.sort(
            function (a, b) {

                const priceA =
                    parseFloat(
                        a.dataset.price
                    );


                const priceB =
                    parseFloat(
                        b.dataset.price
                    );


                const departureA =
                    parseInt(
                        a.dataset.departure
                    );


                const departureB =
                    parseInt(
                        b.dataset.departure
                    );


                if (
                    sortValue === "price-low"
                ) {

                    return priceA - priceB;

                }


                if (
                    sortValue === "price-high"
                ) {

                    return priceB - priceA;

                }


                if (
                    sortValue ===
                    "departure-early"
                ) {

                    return (
                        departureA -
                        departureB
                    );

                }


                if (
                    sortValue ===
                    "departure-late"
                ) {

                    return (
                        departureB -
                        departureA
                    );

                }


                return 0;

            }
        );


        /*
         * Hide all flights
         */

        flights.forEach(
            function (flight) {

                flight.style.display =
                    "none";

            }
        );


        /*
         * Display filtered flights
         * in sorted order
         */

        visibleFlights.forEach(
            function (flight) {

                flight.style.display =
                    "grid";


                flightList.appendChild(
                    flight
                );

            }
        );


        /*
         * Update result counter
         */

        flightCount.textContent =
            visibleFlights.length +
            (
                visibleFlights.length === 1
                    ? " flight found"
                    : " flights found"
            );


        /*
         * No results message
         */

        if (
            visibleFlights.length === 0
        ) {

            noFilterResults.style.display =
                "block";

        }

        else {

            noFilterResults.style.display =
                "none";

        }

    }


    sortSelect.addEventListener(
        "change",
        updateFlights
    );


    stopsSelect.addEventListener(
        "change",
        updateFlights
    );

});