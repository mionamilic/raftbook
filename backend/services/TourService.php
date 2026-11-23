
<?php

require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/TourDAO.php';

class TourService extends BaseService
{
    public function __construct()
    {
        parent::__construct(new TourDAO());
    }

    public function create($data)
    {
        if (empty($data['title'])) {
            throw new InvalidArgumentException('Tour title is required.');
        }

        if (!isset($data['price']) || !is_numeric($data['price']) || $data['price'] < 0) {
            throw new InvalidArgumentException('Valid tour price is required.');
        }

        return parent::create($data);
    }
}
