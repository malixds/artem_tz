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
    public function delete(string $uuid): void
    {
        User::query()->where('id', $uuid)->firstOrFail()->delete();
    }
    public function find(int $id): User
    {
        return User::query()->find($id);
    }
    public function update(string $uuid, array $data): void
    {
        User::query()->where('id', $uuid)->firstOrFail()->update($data);
    }

    public function get(): Collection
    {
        return User::query()->get();
    }

    public function findOrFail(string $uuid): ?User
    {
        return User::query()->where('id', $uuid)->firstOrFail();
    }
}
