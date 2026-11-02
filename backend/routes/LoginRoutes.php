
<?php

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: http://127.0.0.1:5500");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Credentials: true");

if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(204);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode(["error" => "Method not allowed"]);
    exit;
}

require_once __DIR__ . "/../dao/UserDAO.php";
session_start();

try {
    $data = json_decode(file_get_contents("php://input"), true);

    if (!is_array($data)) {
        http_response_code(400);
        echo json_encode(["error" => "Invalid request data."]);
        exit;
    }

    $email = trim($data["email"] ?? "");
    $password = $data["password"] ?? "";

    if ($email === "" || $password === "") {
        http_response_code(400);
        echo json_encode(["error" => "Email and password are required."]);
        exit;
    }

    
    $userDao = new UserDAO();

    $user = $userDao->getByEmail($email);


    if (!$user || !password_verify($password, $user["password_hash"])) {
        http_response_code(401);
        echo json_encode(["error" => "Invalid email or password."]);
        exit;
    }

    session_regenerate_id(true);

    $_SESSION["user_id"] = $user["user_id"];
    $_SESSION["role"] = $user["role"];

    echo json_encode([
        "message" => "Login successful!",
        "user" => [
            "user_id" => $user["user_id"],
            "full_name" => $user["full_name"],
            "email" => $user["email"],
            "role" => $user["role"]
        ]
    ]);

} catch (Throwable $e) {
    error_log($e->getMessage());
    http_response_code(500);
    echo json_encode(["error" => "Server error."]);
}
