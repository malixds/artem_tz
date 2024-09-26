<?php

namespace App\Interfaces;

use App\Http\Requests\CreateUserRequest;
use App\Models\User;
use GuzzleHttp\Promise\Create;
use Illuminate\Database\Eloquent\Collection;

interface IUserRepository
{
    public function create(array $data): User;
    public function delete(string $uuid): void;
    public function find(int $id): User;
    public function update(string $uuid, array $data): void;
    public function get(): Collection;
    public function findOrFail(string $uuid): ?User;

}
