<?php

declare(strict_types=1);

namespace App\Http\Controllers\APIs\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\APIs\Auth\LoginRequest;
use App\Http\Resources\APIs\Auth\LoginResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

final class LoginController extends Controller
{
    public function __invoke(LoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();
        $user = User::where('email', $credentials['email'])->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            return $this->errorResponse([], 'The provided credentials are incorrect.', false, Response::HTTP_UNAUTHORIZED);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->successResponse([
            'access_token' => $token,
            'user' => LoginResource::make($user),
            'token_type' => 'Bearer',
        ], 'Login successful.');
    }
}
