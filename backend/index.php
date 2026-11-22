
<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');



require_once __DIR__ . '/vendor/autoload.php';

Flight::route('GET /', function () {
    Flight::json([
        'message' => 'RaftBook API is running',
        'milestone' => 3
    ]);
});

Flight::route('GET /api/health', function () {
    Flight::json([
        'status' => 'success',
        'message' => 'RaftBook backend is working'
    ]);
});






Flight::route('GET /api/docs', function () {
    require_once __DIR__ . '/docs/openapi.php';

    $openapi = \OpenApi\Generator::scan([
        __DIR__ . '/docs'
    ]);

    header('Content-Type: application/json');
    echo $openapi->toJson();
});





Flight::route('GET /api/swagger', function () {
    header('Content-Type: text/html; charset=utf-8');

    echo <<<'HTML'
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>RaftBook API Documentation</title>
    <link rel="stylesheet" href="https://unpkg.com/swagger-ui-dist@5/swagger-ui.css">
</head>
<body>
    <div id="swagger-ui"></div>

    <script src="https://unpkg.com/swagger-ui-dist@5/swagger-ui-bundle.js"></script>
    <script>
        window.onload = function () {
            SwaggerUIBundle({
                url: '/api/docs',
                dom_id: '#swagger-ui',
                deepLinking: true
            });
        };
    </script>
</body>
</html>
HTML;
});

require_once __DIR__ . '/routes/ApiRoutes.php';
Flight::start();
