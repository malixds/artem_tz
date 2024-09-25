<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Interfaces\ICartRepository;
use App\Interfaces\IUserRepository;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController
{

    private $repository;
    private $repositoryCart;

    public function __construct(IUserRepository $repository, ICartRepository $repositoryCart)
    {
        $this->repository = $repository;
        $this->repositoryCart = $repositoryCart;
    }

    public function register(CreateUserRequest $request): JsonResponse
    {
        $user = $this->repository->create($request->validated());

        return response()->json([
            'token' => $user->createToken('auth_token')->plainTextToken
        ]);
    }

    public function login(LoginUserRequest $request): JsonResponse
    {
        $user = User::query()
            ->whereEmail($request->email)
            ->first();
        if (!Hash::check($request->password, $user->password)) {
            abort(403);
        }
        return response()->json([
            'token' => $user->createToken('auth_token')->plainTextToken
        ]);
    }

    public function password(int $id): void
    {
        $this->repository->update($id,
            ['password' => 'testtest']
        );
    }

    public function users(int $id = null): JsonResponse
    {
        auth()->user()->isAuth();
        return $id ? response()
            ->json($this->repository->find($id)) : response()->json($this->repository->get());
    }

    public function update(int $id, UpdateUserRequest $request): JsonResponse
    {
        auth()->user()->isAuth();
        $this->repository->update($id, $request->validated($id));
        return response()->json('Updated');
    }

    public function cart($id): JsonResponse
    {
        auth()->user()->isAuth();
        try {
            DB::transaction(function () use ($id) {
                $user = $this->repository->find($id);
                $this->repositoryCart->create([
                    "email" => $user->email,
                    "name" => $user->name,
                    "password" => $user->password,
                ]);
                $this->repository->delete($id);
            });
            return response()->json('Success');
        } catch (Exception $e) {
            return response()->json(['error' => 'Transaction failed: ' . $e->getMessage()], 500);
        }
    }

    public function recover(int $id): JsonResponse
    {
        try {
            DB::transaction(function () use ($id) {
                $user = $this->repositoryCart->find($id);
                $this->repository->create([
                    "email" => $user->email,
                    "name" => $user->name,
                    "password" => $user->password,
                ]);
                $this->repositoryCart->delete($id);
            });
            return response()->json('Success');
        } catch (Exception $e) {
            return response()->json(['error' => 'Transaction failed: ' . $e->getMessage()], 500);
        }
    }

    public function delete(int $id): JsonResponse
    {
        $this->repository->delete($id);
        return response()->json('Deleted');
    }

    public function deleteGroup(array $ids): JsonResponse
    {
        foreach ($ids as $id) {
            $this->delete($id);
        }
        return response()->json('Success');
    }

    public function cartGroup(array $ids): JsonResponse
    {
        foreach ($ids as $id) {
            $this->cart($id);
        }
        return response()->json('Success');
    }

    public function recoverGroup(array $ids): JsonResponse
    {
        foreach ($ids as $id) {
            $this->recover($id);
        }
        return response()->json('Success');
    }

}
