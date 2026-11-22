
<?php

require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/CategoryDAO.php';

class CategoryService extends BaseService
{
    public function __construct()
    {
        parent::__construct(new CategoryDAO());
    }

    public function create($data)
    {
        if (empty($data['name'])) {
            throw new InvalidArgumentException('Category name is required.');
        }

        return parent::create($data);
    }
}
