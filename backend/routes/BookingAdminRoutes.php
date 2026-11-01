
<?php

require_once __DIR__ . "/CrudHandler.php";

$dao = new BookingDAO();

$allowedColumns = [
    "user_id",
    "tour_id",
    "booking_date",
    "participants",
    "status"
];

handleCrud($dao, $allowedColumns);
