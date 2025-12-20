<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\PersonalAccessToken;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class AuthenticateWithMongoToken
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();

        Log::info('Auth Middleware - Token received:', ['token' => $token]);

        if (!$token) {
            Log::warning('Auth Middleware - No token provided');
            return response()->json([
                'success' => false,
                'message' => 'No token provided.',
            ], 401);
        }

        // Parse token (format: {id}|{plainTextToken})
        if (strpos($token, '|') !== false) {
            [$id, $plainTextToken] = explode('|', $token, 2);
            
            Log::info('Auth Middleware - Parsed token:', ['id' => $id, 'plainText' => substr($plainTextToken, 0, 10) . '...']);
            
            // Find token by ID
            $tokenModel = PersonalAccessToken::find($id);
            
            Log::info('Auth Middleware - Token model found:', ['found' => $tokenModel ? 'yes' : 'no']);
            
            if ($tokenModel) {
                $hashedInput = hash('sha256', $plainTextToken);
                $matches = hash_equals($tokenModel->token, $hashedInput);
                
                Log::info('Auth Middleware - Hash comparison:', [
                    'stored' => substr($tokenModel->token, 0, 20),
                    'input' => substr($hashedInput, 0, 20),
                    'matches' => $matches
                ]);
                
                if ($matches) {
                    // Token is valid, load the user
                    $user = User::find($tokenModel->tokenable_id);
                    
                    Log::info('Auth Middleware - User found:', ['found' => $user ? 'yes' : 'no', 'id' => $tokenModel->tokenable_id]);
                    
                    if ($user) {
                        // Set the authenticated user
                        $request->setUserResolver(function () use ($user) {
                            return $user;
                        });
                        
                        // Update last used timestamp
                        $tokenModel->last_used_at = now();
                        $tokenModel->save();
                        
                        Log::info('Auth Middleware - Authentication successful');
                        return $next($request);
                    }
                }
            }
        } else {
            Log::warning('Auth Middleware - Token format invalid (no | separator)');
        }

        Log::warning('Auth Middleware - Authentication failed');
        return response()->json([
            'success' => false,
            'message' => 'Unauthenticated.',
        ], 401);
    }
}
