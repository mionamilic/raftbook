
<?php

require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/UserDAO.php';

class UserService extends BaseService
{
    public function __construct()
    {
        parent::__construct(new UserDAO());
    }

    public function create($data)
    {
        if (empty($data['full_name'])) {
            throw new InvalidArgumentException('Full name is required.');
        }

        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Valid email is required.');
        }

        if (empty($data['password']) || strlen($data['password']) < 8) {
            throw new InvalidArgumentException('Password must contain at least 8 characters.');
        }

        $data['password_hash'] = password_hash($data['password'], PASSWORD_DEFAULT);
        unset($data['password']);
        $data['role'] = 'customer';

        return parent::create($data);
    }

    public function getAll()
    {
        return array_map(
            fn($user) => $this->hidePassword($user),
            parent::getAll()
        );
    }

    public function getById($id)
    {
        $user = parent::getById($id);
        return $user ? $this->hidePassword($user) : null;
    }

    private function hidePassword($user)
    {
        unset($user['password_hash']);
        return $user;
    }
}
