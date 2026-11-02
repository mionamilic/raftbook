
<?php

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: http://127.0.0.1:5500");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(204);
    exit;
}

require_once __DIR__ . "/../dao/TourDAO.php";

session_start();

$method = $_SERVER["REQUEST_METHOD"];

try {
    $tourDao = new TourDAO();

    if ($method === "GET") {
        if (isset($_GET["id"])) {
            $tour = $tourDao->getById($_GET["id"]);

            if (!$tour) {
                http_response_code(404);
                echo json_encode(["error" => "Tour not found."]);
                exit;
            }

            echo json_encode($tour);
        } else {
            echo json_encode($tourDao->getAll());
        }

        exit;
    }

    // Only admins can modify tours
    if (
        !isset($_SESSION["user_id"]) ||
        ($_SESSION["role"] ?? "") !== "admin"
    ) {
        http_response_code(403);
        echo json_encode(["error" => "Admin access required."]);
        exit;
    }
    
if ($method === "DELETE") {
    $id = filter_var(
        $_GET["id"] ?? null,
        FILTER_VALIDATE_INT,
        ["options" => ["min_range" => 1]]
    );

    if (!$id) {
        http_response_code(400);
        echo json_encode(["error" => "Valid tour ID is required."]);
        exit;
    }

    if (!$tourDao->getById($id)) {
        http_response_code(404);
        echo json_encode(["error" => "Tour not found."]);
        exit;
    }

    $tourDao->delete($id);

    echo json_encode(["message" => "Tour deleted."]);
    exit;
}

if (!in_array($method, ["POST", "PUT"], true)) {
    http_response_code(405);
    header("Allow: GET, POST, PUT, DELETE, OPTIONS");
    echo json_encode(["error" => "Method not allowed."]);
    exit;
}

    $data = json_decode(file_get_contents("php://input"), true);

    if (!is_array($data)) {
        http_response_code(400);
        echo json_encode(["error" => "Invalid JSON data."]);
        exit;
    }

    // Only allow known database columns
    $allowedColumns = [
        "category_id",
        "title",
        "description",
        "location",
        "duration",
        "price"
    ];

    $data = array_intersect_key(
        $data,
        array_flip($allowedColumns)
    );

    if (empty($data)) {
        http_response_code(400);
        echo json_encode(["error" => "No valid fields provided."]);
        exit;
    }

    if ($method === "POST") {
        $id = $tourDao->insert($data);

        http_response_code(201);
        echo json_encode([
            "message" => "Tour created.",
            "tour_id" => $id
        ]);
        exit;
    }

    if ($method === "PUT") {
        if (!isset($_GET["id"])) {
            http_response_code(400);
            echo json_encode(["error" => "Tour ID is required."]);
            exit;
        }

        if (!$tourDao->getById($_GET["id"])) {
            http_response_code(404);
            echo json_encode(["error" => "Tour not found."]);
            exit;
        }

        $tourDao->update($_GET["id"], $data);
        echo json_encode(["message" => "Tour updated."]);
        exit;
    }

    

} catch (Throwable $e) {
    error_log($e->getMessage());
    http_response_code(500);
    echo json_encode(["error" => "Server error."]);
}
