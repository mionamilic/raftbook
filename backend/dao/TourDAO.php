
<?php

require_once __DIR__ . "/BaseDao.php";

class TourDAO extends BaseDao {

    public function __construct() {
        parent::__construct("tours", "tour_id");
    }

}
