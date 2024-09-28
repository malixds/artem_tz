<?php

namespace App\Http\Controllers;

use App\Enums\ActionEnum;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Interfaces\ICartRepository;
use App\Interfaces\IUserRepository;
use App\Models\History;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController
{
    private IUserRepository $repository;
    private $repositoryCart;

    public function __construct(IUserRepository $repository, ICartRepository $repositoryCart)
    {
        $this->repository = $repository;
        $this->repositoryCart = $repositoryCart;
    }

    public function register(CreateUserRequest $request): JsonResponse
    {
        $user = $this->repository->create($request->validated());
        History::logAction($user, ActionEnum::REGISTER);
        return response()->json([
            'token' => $user->createToken('auth_token')->plainTextToken,
            'id' => $user->id
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
        History::logAction($user, ActionEnum::LOGIN);
        return response()->json([
            'token' => $user->createToken('auth_token')->plainTextToken,
            'id' => $user->id
        ]);
    }

    public function password(string $uuid): JsonResponse
    {
        $user = $this->repository->findOrFail($uuid);
        $user->password = 'adminadmin';
        $user->save();
        History::logAction($user, ActionEnum::PASSWORD);
        return response()->json('Changed');
    }

    public function users(string $uuid = null): JsonResponse
    {
        return $uuid ? response()
            ->json($this->repository->findOrFail($uuid)) : response()->json($this->repository->get());
    }

    public function update(string $uuid, UpdateUserRequest $request): JsonResponse
    {
        $user = $this->repository->findOrFail($uuid);
        $user = $this->repository->update($user, $request->validated());
        History::logAction($user, ActionEnum::UPDATE);
        return response()->json('Updated');
    }

    public function cart(string $uuid): JsonResponse
    {
        try {
            DB::transaction(function () use ($uuid) {
                $user = $this->repository->findOrFail($uuid);
                $this->repositoryCart->create([
                    "user_id" => $user->id,
                    "email" => $user->email,
                    "name" => $user->name,
                    "password" => $user->password,
                ]);
                $this->repository->delete($uuid);
                History::logAction($user, ActionEnum::CART);
            });
            return response()->json('Success');
        } catch (Exception $e) {
            return response()->json(['error' => 'Transaction failed: ' . $e->getMessage()], 500);
        }
    }

    public function recover(string $uuid): JsonResponse
    {
        try {
            DB::transaction(function () use ($uuid) {
                $user = $this->repositoryCart->findOrFail($uuid);
                $user = $this->repository->create([
                    "id" => $uuid,
                    "email" => $user->email,
                    "name" => $user->name,
                    "password" => $user->password,
                ]);
                $this->repositoryCart->delete($uuid);
                History::logAction($user, ActionEnum::RECOVER);
            });
            return response()->json('Success');
        } catch (Exception $e) {
            return response()->json(['error' => 'Transaction failed: ' . $e->getMessage()], 500);
        }
    }

    public function delete(string $uuid): JsonResponse
    {
        $this->repository->delete($uuid);
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
