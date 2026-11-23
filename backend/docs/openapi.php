
<?php

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="RaftBook API",
 *     version="1.0.0",
 *     description="REST API for RaftBook application"
 * )
 *
 * @OA\Server(
 *     url="http://127.0.0.1:8000"
 * )
 */
class OpenApiDocumentation
{
    /**
     * @OA\Get(
     *     path="/api/tours",
     *     summary="Get all tours",
     *     tags={"Tours"},
     *     @OA\Response(response=200, description="List of tours")
     * )
     */
    public function getTours() {}

    /**
     * @OA\Get(
     *     path="/api/categories",
     *     summary="Get all categories",
     *     tags={"Categories"},
     *     @OA\Response(response=200, description="List of categories")
     * )
     */
    public function getCategories() {}

    /**
     * @OA\Get(
     *     path="/api/bookings",
     *     summary="Get all bookings",
     *     tags={"Bookings"},
     *     @OA\Response(response=200, description="List of bookings")
     * )
     */
    public function getBookings() {}

    /**
     * @OA\Get(
     *     path="/api/reviews",
     *     summary="Get all reviews",
     *     tags={"Reviews"},
     *     @OA\Response(response=200, description="List of reviews")
     * )
     */
    public function getReviews() {}
}
