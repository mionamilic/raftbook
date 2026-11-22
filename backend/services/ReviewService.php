
<?php

require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/ReviewDAO.php';

class ReviewService extends BaseService
{
    public function __construct()
    {
        parent::__construct(new ReviewDAO());
    }

    public function create($data)
    {
        if (empty($data['user_id']) || empty($data['tour_id'])) {
            throw new InvalidArgumentException('User and tour are required.');
        }

        if (!isset($data['rating']) || $data['rating'] < 1 || $data['rating'] > 5) {
            throw new InvalidArgumentException('Rating must be between 1 and 5.');
        }

        return parent::create($data);
    }
}
