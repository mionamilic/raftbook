
<?php

require_once __DIR__ . "/BaseDao.php";

class CategoryDAO extends BaseDao {

    public function __construct() {
        parent::__construct("categories", "category_id");
    }

}
