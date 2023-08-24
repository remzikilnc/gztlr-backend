<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginUserRequest;
use App\Http\Requests\Auth\RegisterUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class JwtAuthController extends Controller
{
    /**
     * Register a new user and return an access token.
     */
    public function register(RegisterUserRequest $request): JsonResponse
    {
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        event(new Registered($user));

        auth()->login($user);

        $token = auth('api')->tokenById($user->id);

        return $this->respondWithToken($token);
    }

    /**
     * Authenticate a user and return an access token.
     */
    public function login(LoginUserRequest $request): JsonResponse
    {
        $token = Auth::attempt($request->only(['email', 'password']));

        if (!$token) {
            return response()->error('wrong-credentials');
        }

        return $this->respondWithToken($token);
    }

    /**
     * Log out the currently authenticated user (invalidate the token).
     */
    public function logout(): Response
    {
        Auth::logout();

        return response()->noContent();
    }

    /**
     * Refresh the currently authenticated user's access token.
     */
    public function refresh(): JsonResponse
    {
        $token = Auth::refresh();

        if ($token){
            return response()->ok([
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 60
            ]);
        }else{
            return response()->forbidden();
        }


    }

    protected function respondWithToken(string $token): JsonResponse
    {
        return response()->ok([
            'user' =>  new UserResource(Auth::user()),
            'token' => [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 60
            ]
        ]);
    }
}
