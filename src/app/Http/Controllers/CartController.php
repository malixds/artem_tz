<?php

namespace App\Http\Controllers;

use App\Interfaces\ICartRepository;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected $repository;
    public function __construct(ICartRepository $repository)
    {
        $this->repository = $repository;
    }

    public function cart()
    {
        return response()->json($this->repository->get());
    }
}
