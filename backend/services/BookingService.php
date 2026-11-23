
<?php

require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/BookingDAO.php';

class BookingService extends BaseService
{
    public function __construct()
    {
        parent::__construct(new BookingDAO());
    }

    public function create($data)
    {
        if (empty($data['user_id']) || empty($data['tour_id']) || empty($data['booking_date'])) {
            throw new InvalidArgumentException('User, tour and booking date are required.');
        }

        if (empty($data['participants']) || $data['participants'] < 1) {
            throw new InvalidArgumentException('Participants must be at least 1.');
        }

        return parent::create($data);
    }
}
