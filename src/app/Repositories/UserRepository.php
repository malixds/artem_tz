<?php

namespace App\Repositories;

use App\Http\Requests\CreateUserRequest;
use App\Interfaces\IUserRepository;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class UserRepository implements IUserRepository
{
    public function create(array $data): User
    {
        return User::query()->create($data);
    }
    public function delete(int $id): void
    {
        User::query()->find($id)->delete();
    }
    public function find(int $id): User
    {
        return User::query()->find($id);
    }
    public function update(int $id, array $data): void
    {
        User::query()->find($id)->update($data);
    }

    public function get(): Collection
    {
        return User::query()->get();
    }
}
