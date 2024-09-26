<?php

namespace App\Interfaces;

use App\Models\Cart;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface ICartRepository
{
    public function create(array $data): Cart;
    public function delete(string $uuid): void;
    public function find(int $id): Cart;
    public function get(): Collection;
    public function findOrFail(string $uuid): ?Cart;


}
