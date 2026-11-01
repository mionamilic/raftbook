
<?php

require_once __DIR__ . "/BaseDao.php";

class BookingDAO extends BaseDao {

    public function __construct() {
        parent::__construct("bookings", "booking_id");
    }

}
