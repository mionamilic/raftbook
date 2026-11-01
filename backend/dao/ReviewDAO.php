
<?php

require_once __DIR__ . "/BaseDao.php";

class ReviewDAO extends BaseDao {

    public function __construct() {
        parent::__construct("reviews", "review_id");
    }

}
