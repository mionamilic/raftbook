
<?php

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: http://127.0.0.1:5500");
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

require_once __DIR__ . "/../dao/UserDAO.php";
try {
    $data = json_decode(file_get_contents("php://input"), true);

    if (!is_array($data)) {
        throw new InvalidArgumentException("Invalid JSON data.");
    }

    $fullName = trim($data["full_name"] ?? "");
    $email = trim($data["email"] ?? "");
    $password = $data["password"] ?? "";

    if ($fullName === "" || $email === "" || $password === "") {
        http_response_code(400);
        echo json_encode(["error" => "All fields are required."]);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(["error" => "Invalid email address."]);
        exit;
    }

    if (strlen($password) < 8) {
        http_response_code(400);
        echo json_encode(["error" => "Password must contain at least 8 characters."]);
        exit;
    }


    $userDao = new UserDAO();

    // Check if email already exists
    $existingUser = $userDao->getByEmail($email);

    if ($existingUser) {
        http_response_code(409);
        echo json_encode(["error" => "Email is already registered."]);
        exit;
    }

    // Hash the password
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // Insert new user using DAO
    $userDao->insert([
        "full_name" => $fullName,
        "email" => $email,
        "password_hash" => $passwordHash,
        "role" => "customer"
    ]);


    http_response_code(201);

    echo json_encode([
        "message" => "Registration successful!"
    ]);

} catch (InvalidArgumentException $e) {
    http_response_code(400);
    echo json_encode(["error" => $e->getMessage()]);

} catch (PDOException $e) {
    error_log($e->getMessage());
    http_response_code(500);
    echo json_encode(["error" => "Database error."]);

} catch (Throwable $e) {
    error_log($e->getMessage());
    http_response_code(500);
    echo json_encode(["error" => "Server error."]);
}
