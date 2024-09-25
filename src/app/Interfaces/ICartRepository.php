<?php

namespace App\Interfaces;

use App\Models\Cart;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface ICartRepository
{
    public function create(array $data): Cart;
    public function delete(int $id): void;
    public function find(int $id): Cart;
    public function get(): Collection;


}
