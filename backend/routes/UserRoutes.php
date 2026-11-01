
<?php

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: http://127.0.0.1:5500");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(204);
    exit;
}

require_once __DIR__ . "/../dao/UserDAO.php";

session_start();

function respond($status, $data) {
    http_response_code($status);
    echo json_encode($data);
    exit;
}

function publicUser($user) {
    unset($user["password_hash"]);
    return $user;
}

try {
    if (
        !isset($_SESSION["user_id"]) ||
        ($_SESSION["role"] ?? "") !== "admin"
    ) {
        respond(403, ["error" => "Admin access required."]);
    }

    $dao = new UserDAO();
    $method = $_SERVER["REQUEST_METHOD"];

    if ($method === "GET") {
        if (isset($_GET["id"])) {
            $user = $dao->getById($_GET["id"]);

            if (!$user) {
                respond(404, ["error" => "User not found."]);
            }

            respond(200, publicUser($user));
        }

        $users = array_map(
            "publicUser",
            $dao->getAll()
        );

        respond(200, $users);
    }

    if ($method === "DELETE") {
        $id = filter_var(
            $_GET["id"] ?? null,
            FILTER_VALIDATE_INT,
            ["options" => ["min_range" => 1]]
        );

        if (!$id) {
            respond(400, ["error" => "Valid user ID required."]);
        }

        if (!$dao->getById($id)) {
            respond(404, ["error" => "User not found."]);
        }

        if ($id == $_SESSION["user_id"]) {
            respond(400, ["error" => "You cannot delete your own account."]);
        }

        $dao->delete($id);
        respond(200, ["message" => "User deleted."]);
    }

    if (!in_array($method, ["POST", "PUT", "PATCH"], true)) {
        respond(405, ["error" => "Method not allowed."]);
    }

    $data = json_decode(file_get_contents("php://input"), true);

    if (!is_array($data)) {
        respond(400, ["error" => "Invalid JSON data."]);
    }

    $allowed = ["full_name", "email", "password"];
    $data = array_intersect_key($data, array_flip($allowed));

    if (isset($data["email"]) &&
        !filter_var($data["email"], FILTER_VALIDATE_EMAIL)) {
        respond(400, ["error" => "Invalid email."]);
    }

    if (isset($data["password"])) {
        if (!is_string($data["password"]) ||
            strlen($data["password"]) < 8) {
            respond(400, ["error" => "Password must have at least 8 characters."]);
        }

        $data["password_hash"] = password_hash(
            $data["password"],
            PASSWORD_DEFAULT
        );

        unset($data["password"]);
    }

    if ($method === "POST") {
        if (
            empty($data["full_name"]) ||
            empty($data["email"]) ||
            empty($data["password_hash"])
        ) {
            respond(400, ["error" => "Name, email and password required."]);
        }

        $data["role"] = "customer";

        $id = $dao->insert($data);
        respond(201, [
            "message" => "User created.",
            "user_id" => $id
        ]);
    }

    $id = filter_var(
        $_GET["id"] ?? null,
        FILTER_VALIDATE_INT,
        ["options" => ["min_range" => 1]]
    );

    if (!$id) {
        respond(400, ["error" => "Valid user ID required."]);
    }

    if (!$dao->getById($id)) {
        respond(404, ["error" => "User not found."]);
    }

    if (empty($data)) {
        respond(400, ["error" => "No valid fields provided."]);
    }

    $dao->update($id, $data);

    respond(200, ["message" => "User updated."]);

} catch (Throwable $e) {
    error_log($e->getMessage());
    respond(500, ["error" => "Server error."]);
}
