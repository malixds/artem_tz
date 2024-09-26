<?php

namespace App\Repositories;

use App\Interfaces\ICartRepository;
use App\Models\Cart;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use function Illuminate\Events\queueable;

class CartRepository implements ICartRepository
{
    public function create(array $data): Cart
    {
        return Cart::query()->create($data);
    }
    public function delete(string $uuid): void
    {
        Cart::query()->where('user_id', $uuid)->firstOrFail()->delete();
    }
    public function find(int $id): Cart
    {
        return Cart::query()->find($id);
    }
    public function get(): Collection
    {
        return Cart::query()->get();
    }
    public function findOrFail(string $uuid): ?Cart
    {
        return Cart::query()->where('user_id', $uuid)->firstOrFail();
    }
}
