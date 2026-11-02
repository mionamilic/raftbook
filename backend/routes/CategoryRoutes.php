
<?php

require_once __DIR__ . "/CrudHandler.php";

$dao = new CategoryDAO();

$allowedColumns = [
    "name",
    "description"
];

handleCrud($dao, $allowedColumns);
