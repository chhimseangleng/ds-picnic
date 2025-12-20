<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PersonalAccessToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Login user and create token
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'The provided credentials are incorrect.',
            ], 401);
        }

        // Generate a simple token
        $plainTextToken = Str::random(60);
        $hashedToken = hash('sha256', $plainTextToken);

        // Create token in MongoDB
        $tokenModel = PersonalAccessToken::create([
            'tokenable_type' => get_class($user),
            'tokenable_id' => $user->_id,
            'name' => 'mobile-app',
            'token' => $hashedToken,
            'abilities' => ['*'],
            'expires_at' => null,
        ]);

        // Return format: {token_id}|{plain_text_token}
        $token = $tokenModel->_id . '|' . $plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'data' => [
                'user' => $user,
                'token' => $token,
            ],
        ]);
    }

    /**
     * Register new user
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Generate a simple token
        $plainTextToken = Str::random(60);
        $hashedToken = hash('sha256', $plainTextToken);

        // Create token in MongoDB
        $tokenModel = PersonalAccessToken::create([
            'tokenable_type' => get_class($user),
            'tokenable_id' => $user->_id,
            'name' => 'mobile-app',
            'token' => $hashedToken,
            'abilities' => ['*'],
            'expires_at' => null,
        ]);

        // Return format: {token_id}|{plain_text_token}
        $token = $tokenModel->_id . '|' . $plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Registration successful',
            'data' => [
                'user' => $user,
                'token' => $token,
            ],
        ], 201);
    }

    /**
     * Logout user (revoke token)
     */
    public function logout(Request $request)
    {
        $user = $request->user();
        
        // Delete all tokens for this user
        PersonalAccessToken::where('tokenable_id', $user->_id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully',
        ]);
    }

    /**
     * Get authenticated user
     */
    public function user(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => $request->user(),
        ]);
    }
}
