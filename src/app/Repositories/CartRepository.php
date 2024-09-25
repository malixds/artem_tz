<?php

namespace App\Repositories;

use App\Interfaces\ICartRepository;
use App\Models\Cart;
use Illuminate\Database\Eloquent\Collection;

class CartRepository implements ICartRepository
{
    public function create(array $data): Cart
    {
        return Cart::query()->create($data);
    }
    public function delete(int $id): void
    {
        Cart::query()->find($id)->delete();
    }
    public function find(int $id): Cart
    {
        return Cart::query()->find($id);
    }
    public function get(): Collection
    {
        return Cart::query()->get();
    }
}
