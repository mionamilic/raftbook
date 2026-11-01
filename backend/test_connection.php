
<?php

require_once __DIR__ . "/config/Database.php";

try {
    $pdo = Database::connect();
    echo "SUCCESS! Connected to raftbook database.";
} catch (Exception $e) {
    echo "ERROR! Could not connect to database.";
}
