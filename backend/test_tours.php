<?php

require_once __DIR__ . '/dao/TourDAO.php';

try {
    $tourDAO = new TourDAO();

    $tours = $tourDAO->getAllTours();

    header('Content-Type: application/json; charset=utf-8');

    echo json_encode($tours, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    http_response_code(500);
    echo "ERROR: Could not load tours.";
}