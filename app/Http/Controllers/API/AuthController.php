<?php

namespace App\Http\Controllers\API;

use App\Enum\StatusEnum;
use App\Http\Transformers\UserTransformer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class AuthController extends ApiController
{
    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required',
            ]);

            if ($validator->fails()) {
                return $this->errorResponse('Invalid email or password.', StatusEnum::BAD_REQUEST);
            }
            if (!Auth::attempt($request->only('email', 'password'))) {
                return $this->errorResponse('Invalid credentials.', StatusEnum::UNAUTHORIZED);
            }

            $user = Auth::user();
            $token = $user->createToken('api_token')->plainTextToken;
            $user->access_token = $token;

            return $this->successResponse((new UserTransformer)->transform($user), 'Logged in successfully.', StatusEnum::OK);
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage(), StatusEnum::SERVER_ERROR);
        }
    }

    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
            ]);

            if ($validator->fails()) {
                return $this->errorResponse('Invalid name, email or password.', StatusEnum::BAD_REQUEST);
            }
            
            $user = User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
            ]);

            return $this->successResponse((new UserTransformer)->transform($user), 'Registered successfully.', StatusEnum::OK);
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage(), StatusEnum::SERVER_ERROR);
        }
    }

    public function user(Request $request)
    {
        try {
            $user = $request->user();
            $user->access_token = $request->bearerToken();

            return $this->successResponse((new UserTransformer)->transform($user), 'User information retrieved successfully.', StatusEnum::OK);
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage(), StatusEnum::SERVER_ERROR);
        }
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();
            Auth::forgetGuards();

            return $this->successResponse(null, 'User logged out successfully.', StatusEnum::OK);
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage(), StatusEnum::SERVER_ERROR);
        }
    }
}
