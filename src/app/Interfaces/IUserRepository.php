<?php

namespace App\Interfaces;

use App\Http\Requests\CreateUserRequest;
use App\Models\User;
use GuzzleHttp\Promise\Create;
use Illuminate\Database\Eloquent\Collection;

interface IUserRepository
{
    public function create(array $data): User;
    public function delete(int $id): void;
    public function find(int $id): User;
    public function update(int $id, array $data): void;
    public function get(): Collection;


}
