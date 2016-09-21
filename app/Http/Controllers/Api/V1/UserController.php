<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseController;
use App\Repositories\UserRepository as User;
use App\Transformers\UserTransformer;

class UserController extends BaseController
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function user()
    {
        $user = $this->user->find(2);

        return $this->response->item($user, new UserTransformer);
    }

    public function users()
    {
        $users = $this->user->paginate(10);

        return $this->response->paginator($users, new UserTransformer);
    }
}
