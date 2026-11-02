
<?php

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: http://127.0.0.1:5500");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(204);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "GET") {
    http_response_code(405);
    echo json_encode(["error" => "Method not allowed"]);
    exit;
}

session_start();

if (!isset($_SESSION["user_id"])) {
    http_response_code(401);
    echo json_encode([
        "authenticated" => false,
        "message" => "User is not logged in."
    ]);
    exit;
}

echo json_encode([
    "authenticated" => true,
    "user_id" => $_SESSION["user_id"],
    "role" => $_SESSION["role"]
]);
