<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginUserRequest;
use App\Http\Requests\Auth\RegisterUserRequest;
use App\Http\Resources\UserResource;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class JwtAuthController extends Controller
{
    protected Role $role;

    public function __construct(Role $role)
    {
        $this->role = $role;
    }
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

        $defaultRole = $this->role->getDefaultRole();

        if ($defaultRole) {
            $user->assignRole($defaultRole);
        }

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
        $token = JWTAuth::getToken();
        if(!$token){
            return response()->badRequest('Token not provided');
        }
        try{
            $token = JWTAuth::refresh($token);
        }catch(TokenInvalidException $e){
            return response()->unauthorized('Token not valid');
        }

        return response()->ok([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
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
