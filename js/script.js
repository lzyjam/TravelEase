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
                    document.getElementById("from")
                        .value.trim();

                const to =
                    document.getElementById("to")
                        .value.trim();

                const date =
                    document.getElementById("date")
                        .value.trim();

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

                        if (selectedDate < today) {

                            errorMessage =
                                "Departure date cannot be in the past.";

                        }

                    }

                }


                /*
                 * Stop submission if validation fails
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
 * City Autocomplete
 * =========================================
 */

document.addEventListener("DOMContentLoaded", function () {

    /*
     * Available cities for the prototype.
     *
     * The array can easily be expanded
     * when more routes are added.
     */

    const cities = [
        "Sydney",
        "Melbourne",
        "Brisbane",
        "Gold Coast",
        "Adelaide",
        "Perth",
        "Canberra",
        "Hobart",
        "Darwin",
        "Singapore"
    ];


    const fromInput =
        document.getElementById("from");

    const toInput =
        document.getElementById("to");


    /*
     * Autocomplete only runs
     * on pages containing the search form
     */

    if (!fromInput || !toInput) {
        return;
    }


    function createAutocomplete(input) {

        /*
         * Create the suggestion box
         * using JavaScript
         */

        const suggestionBox =
            document.createElement("div");


        suggestionBox.className =
            "autocomplete-list";


        /*
         * Position the list underneath
         * the input field
         */

        suggestionBox.style.display =
            "none";

        suggestionBox.style.position =
            "absolute";

        suggestionBox.style.left =
            "0";

        suggestionBox.style.right =
            "0";

        suggestionBox.style.top =
            "100%";

        suggestionBox.style.background =
            "white";

        suggestionBox.style.border =
            "1px solid #ccc";

        suggestionBox.style.borderTop =
            "none";

        suggestionBox.style.borderRadius =
            "0 0 5px 5px";

        suggestionBox.style.zIndex =
            "1000";

        suggestionBox.style.maxHeight =
            "220px";

        suggestionBox.style.overflowY =
            "auto";

        suggestionBox.style.boxShadow =
            "0 4px 8px rgba(0, 0, 0, 0.10)";


        /*
         * Use the input's form-group
         * as the positioning container
         */

        const container =
            input.parentElement;

        container.style.position =
            "relative";

        container.appendChild(
            suggestionBox
        );


        /*
         * Close the suggestion list
         */

        function closeSuggestions() {

            suggestionBox.innerHTML = "";

            suggestionBox.style.display =
                "none";

        }


        /*
         * Create the visible suggestions
         */

        function showSuggestions() {

            const searchText =
                input.value
                    .trim()
                    .toLowerCase();


            /*
             * Do not show the complete city
             * list when nothing has been typed
             */

            if (searchText === "") {

                closeSuggestions();

                return;

            }


            /*
             * FILTER ALGORITHM
             *
             * Keep cities containing the
             * user's search text.
             *
             * Example:
             * "mel" -> Melbourne
             */

            const matches =
                cities.filter(
                    function (city) {

                        return city
                            .toLowerCase()
                            .includes(searchText);

                    }
                );


            suggestionBox.innerHTML = "";


            /*
             * No matching city
             */

            if (matches.length === 0) {

                const noResult =
                    document.createElement("div");

                noResult.textContent =
                    "No matching city";

                noResult.style.padding =
                    "10px 12px";

                noResult.style.color =
                    "#777";

                noResult.style.fontSize =
                    "14px";

                suggestionBox.appendChild(
                    noResult
                );

                suggestionBox.style.display =
                    "block";

                return;

            }


            /*
             * Create a suggestion element
             * for every matching city
             */

            matches.forEach(
                function (city) {

                    const item =
                        document.createElement("div");


                    item.textContent =
                        city;


                    item.style.padding =
                        "10px 12px";


                    item.style.cursor =
                        "pointer";


                    item.style.borderBottom =
                        "1px solid #eee";


                    /*
                     * Mouse hover feedback
                     */

                    item.addEventListener(
                        "mouseenter",
                        function () {

                            item.style.background =
                                "#f1f6fc";

                        }
                    );


                    item.addEventListener(
                        "mouseleave",
                        function () {

                            item.style.background =
                                "white";

                        }
                    );


                    /*
                     * Select a city
                     */

                    item.addEventListener(
                        "mousedown",
                        function (event) {

                            /*
                             * Prevent the input losing
                             * focus before selection
                             */

                            event.preventDefault();

                            input.value =
                                city;

                            closeSuggestions();

                        }
                    );


                    suggestionBox.appendChild(
                        item
                    );

                }
            );


            suggestionBox.style.display =
                "block";

        }


        /*
         * Update suggestions every time
         * the user types
         */

        input.addEventListener(
            "input",
            showSuggestions
        );


        /*
         * Close when input loses focus
         */

        input.addEventListener(
            "blur",
            function () {

                setTimeout(
                    closeSuggestions,
                    150
                );

            }
        );

    }


    /*
     * Enable autocomplete for
     * both From and To
     */

    createAutocomplete(fromInput);

    createAutocomplete(toInput);

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
             * Keyboard accessibility
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
                 * Remove previous validation error
                 */

                if (errorBox) {

                    errorBox.textContent = "";

                    errorBox.style.display =
                        "none";

                }


                /*
                 * Move directly to date
                 */

                dateInput.focus();


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


                    if (stopsValue === "all") {

                        return true;

                    }


                    if (stopsValue === "direct") {

                        return (
                            stops === "direct" ||
                            stops === "0" ||
                            stops === "0 stops"
                        );

                    }


                    if (stopsValue === "1") {

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


                if (sortValue === "price-low") {

                    return priceA - priceB;

                }


                if (sortValue === "price-high") {

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
         * using Stage 6 CSS Grid layout
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