
<?php

require_once __DIR__ . "/../dao/UserDAO.php";
require_once __DIR__ . "/../dao/CategoryDAO.php";
require_once __DIR__ . "/../dao/BookingDAO.php";
require_once __DIR__ . "/../dao/ReviewDAO.php";

function handleCrud($dao, $allowedColumns, $roleRequired = "admin")
{
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Origin: http://127.0.0.1:5500");
    header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type");

    if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
        http_response_code(204);
        exit;
    }

    session_start();

    $method = $_SERVER["REQUEST_METHOD"];

    try {
        if ($method === "GET") {
            if (
                !isset($_SESSION["user_id"]) ||
                ($_SESSION["role"] ?? "") !== $roleRequired
            ) {
                http_response_code(403);
                echo json_encode(["error" => "Access denied."]);
                return;
            }

            if (isset($_GET["id"])) {
                echo json_encode($dao->getById($_GET["id"]]));
            } else {
                echo json_encode($dao->getAll());
            }
            return;
        }

        if (
            !isset($_SESSION["user_id"]) ||
            ($_SESSION["role"] ?? "") !== $roleRequired
        ) {
            http_response_code(403);
            echo json_encode(["error" => "Access denied."]);
            return;
        }

        if ($method === "DELETE") {
            if (!isset($_GET["id"])) {
                http_response_code(400);
                echo json_encode(["error" => "ID required."]);
                return;
            }

            $dao->delete($_GET["id"]);
            echo json_encode(["message" => "Deleted successfully."]);
            return;
        }

        if (!in_array($method, ["POST", "PUT", "PATCH"], true)) {
            http_response_code(405);
            echo json_encode(["error" => "Method not allowed."]);
            return;
        }

        $data = json_decode(file_get_contents("php://input"), true);

        if (!is_array($data)) {
            http_response_code(400);
            echo json_encode(["error" => "Invalid JSON data."]);
            return;
        }

        $data = array_intersect_key(
            $data,
            array_flip($allowedColumns)
        );

        if (empty($data)) {
            http_response_code(400);
            echo json_encode(["error" => "No valid fields provided."]);
            return;
        }

        if ($method === "POST") {
            $id = $dao->insert($data);
            http_response_code(201);
            echo json_encode([
                "message" => "Created successfully.",
                "id" => $id
            ]);
            return;
        }

        if (!isset($_GET["id"])) {
            http_response_code(400);
            echo json_encode(["error" => "ID required."]);
            return;
        }

        $dao->update($_GET["id"], $data);
        echo json_encode(["message" => "Updated successfully."]);

    } catch (Throwable $e) {
        error_log($e->getMessage());
        http_response_code(500);
        echo json_encode(["error" => "Server error."]);
    }
}
