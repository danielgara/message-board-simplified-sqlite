<?php

namespace App\Http\Controllers\V1\Auth;

use App\Http\Controllers\BaseController;
use App\Http\Requests\User\LoginUserRequest;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * This class is responsible for managing all api/v1/auth/* actions.
 */
class AuthController extends BaseController
{
    /**
     * Register a user in storage and return json response with token.
     *
     * @param  \App\Http\Requests\User\StoreUserRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(StoreUserRequest $request): JsonResponse
    {
        $user = User::create($request->validated());
        $user->password = Hash::make($user->password);
        $user->save();

        $responseData = [];
        $responseData['token'] = $user->createToken('auth_token')->plainTextToken;

        return $this->sendResponse($responseData, 201);
    }

    /**
     * Login a user and return json response with token.
     *
     * @param  \App\Http\Requests\User\LoginUserRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginUserRequest $request): JsonResponse
    {
        $responseData = [];
        if (! Auth::attempt($request->only(['email', 'password']))) {
            $responseData['message'] = 'Email & Password does not match with our record.';

            return $this->sendResponseError($responseData, 401);
        }

        $user = Auth::user();
        $responseData['token'] = $user->createToken('auth_token')->plainTextToken;

        return $this->sendResponse($responseData);
    }

    /**
     * Return json response with user data.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUser(User $user): JsonResponse
    {
        $responseData = [];
        $responseData['user'] = new UserResource($user);

        return $this->sendResponse($responseData);
    }
}
