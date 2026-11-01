
<?php

require_once __DIR__ . "/CrudHandler.php";

$dao = new ReviewDAO();

$allowedColumns = [
    "user_id",
    "tour_id",
    "rating",
    "comment"
];

handleCrud($dao, $allowedColumns);
