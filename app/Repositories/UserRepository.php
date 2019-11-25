<?php

namespace App\Repositories;

use App\Models\User;

/**
 * @method \Illuminate\Database\Eloquent\Collection|User[] all()
 * @method User create(array $data)
 * @method User|null get($id)
 */
class UserRepository extends AbstractEloquentRepository
{
    public function __construct()
    {
        $this->model = new User();
    }
}
