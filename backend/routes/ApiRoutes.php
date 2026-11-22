
<?php

require_once __DIR__ . '/../services/TourService.php';
require_once __DIR__ . '/../services/CategoryService.php';
require_once __DIR__ . '/../services/BookingService.php';
require_once __DIR__ . '/../services/ReviewService.php';
require_once __DIR__ . '/../services/UserService.php';

function apiResponse($status, $data)
{
    Flight::json($data, $status);
}

function registerCrudRoutes($resource, $service)
{
    Flight::route("GET /api/$resource", function () use ($service) {
        try {
            apiResponse(200, $service->getAll());
        } catch (Throwable $e) {
            error_log($e->getMessage());
            apiResponse(500, ['error' => 'Server error']);
        }
    });

    Flight::route("GET /api/$resource/@id", function ($id) use ($service) {
        try {
            $item = $service->getById($id);

            if (!$item) {
                apiResponse(404, ['error' => 'Not found']);
                return;
            }

            apiResponse(200, $item);
        } catch (Throwable $e) {
            error_log($e->getMessage());
            apiResponse(500, ['error' => 'Server error']);
        }
    });

    Flight::route("POST /api/$resource", function () use ($service) {
        try {
            $data = Flight::request()->data->getData();
            $id = $service->create($data);

            apiResponse(201, [
                'message' => 'Created successfully',
                'id' => $id
            ]);
        } catch (InvalidArgumentException $e) {
            apiResponse(400, ['error' => $e->getMessage()]);
        } catch (Throwable $e) {
            error_log($e->getMessage());
            apiResponse(500, ['error' => 'Server error']);
        }
    });

    Flight::route("PUT|PATCH /api/$resource/@id", function ($id) use ($service) {
        try {
            $data = json_decode(file_get_contents('php://input'), true);

            if (!is_array($data) || empty($data)) {
                apiResponse(400, ['error' => 'Invalid request data']);
                return;
            }

            if (!$service->getById($id)) {
                apiResponse(404, ['error' => 'Not found']);
                return;
            }

            $service->update($id, $data);
            apiResponse(200, ['message' => 'Updated successfully']);
        } catch (InvalidArgumentException $e) {
            apiResponse(400, ['error' => $e->getMessage()]);
        } catch (Throwable $e) {
            error_log($e->getMessage());
            apiResponse(500, ['error' => 'Server error']);
        }
    });

    Flight::route("DELETE /api/$resource/@id", function ($id) use ($service) {
        try {
            if (!$service->getById($id)) {
                apiResponse(404, ['error' => 'Not found']);
                return;
            }

            $service->delete($id);
            apiResponse(200, ['message' => 'Deleted successfully']);
        } catch (Throwable $e) {
            error_log($e->getMessage());
            apiResponse(500, ['error' => 'Server error']);
        }
    });
}

registerCrudRoutes('tours', new TourService());
registerCrudRoutes('categories', new CategoryService());
registerCrudRoutes('bookings', new BookingService());
registerCrudRoutes('reviews', new ReviewService());
registerCrudRoutes('users', new UserService());
