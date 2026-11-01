
<?php

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: http://127.0.0.1:5500");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(204);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode(["error" => "Method not allowed"]);
    exit;
}

require_once __DIR__ . "/../dao/BookingDAO.php";
require_once __DIR__ . "/../dao/TourDAO.php";

session_start();

try {
    // Check if the user is logged in
    if (!isset($_SESSION["user_id"])) {
        http_response_code(401);
        echo json_encode(["error" => "Please log in first."]);
        exit;
    }

    $data = json_decode(file_get_contents("php://input"), true);

    if (!is_array($data)) {
        http_response_code(400);
        echo json_encode(["error" => "Invalid request data."]);
        exit;
    }

    $tourId = filter_var(
        $data["tour_id"] ?? null,
        FILTER_VALIDATE_INT,
        ["options" => ["min_range" => 1]]
    );

    $participants = filter_var(
        $data["participants"] ?? null,
        FILTER_VALIDATE_INT,
        ["options" => ["min_range" => 1, "max_range" => 50]]
    );

    $bookingDate = $data["booking_date"] ?? "";

    if (!$tourId || !$participants) {
        http_response_code(400);
        echo json_encode(["error" => "Invalid tour or number of participants."]);
        exit;
    }

    // Validate date
    $date = DateTimeImmutable::createFromFormat("!Y-m-d", $bookingDate);

    if (!$date || $date->format("Y-m-d") !== $bookingDate) {
        http_response_code(400);
        echo json_encode(["error" => "Invalid booking date."]);
        exit;
    }

    if ($date < new DateTimeImmutable("today")) {
        http_response_code(400);
        echo json_encode(["error" => "Booking date cannot be in the past."]);
        exit;
    }

    // Check if the selected tour exists
    $tourDao = new TourDAO();
    $tour = $tourDao->getById($tourId);

    if (!$tour) {
        http_response_code(404);
        echo json_encode(["error" => "Tour not found."]);
        exit;
    }

    // Save booking
    $bookingDao = new BookingDAO();

    $bookingId = $bookingDao->insert([
        "user_id" => $_SESSION["user_id"],
        "tour_id" => $tourId,
        "booking_date" => $bookingDate,
        "participants" => $participants,
        "status" => "pending"
    ]);

    http_response_code(201);

    echo json_encode([
        "message" => "Booking created successfully!",
        "booking_id" => $bookingId,
        "status" => "pending"
    ]);

} catch (Throwable $e) {
    error_log($e->getMessage());

    http_response_code(500);
    echo json_encode(["error" => "Server error."]);
}
