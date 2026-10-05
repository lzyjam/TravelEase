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