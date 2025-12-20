<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\PersonalAccessToken;
use App\Models\User;

class AuthenticateWithMongoToken
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        // Parse token (format: {id}|{plainTextToken})
        if (strpos($token, '|') !== false) {
            [$id, $plainTextToken] = explode('|', $token, 2);
            
            // Find token by ID
            $tokenModel = PersonalAccessToken::find($id);
            
            if ($tokenModel && hash_equals($tokenModel->token, hash('sha256', $plainTextToken))) {
                // Token is valid, load the user
                $user = User::find($tokenModel->tokenable_id);
                
                if ($user) {
                    // Set the authenticated user
                    $request->setUserResolver(function () use ($user) {
                        return $user;
                    });
                    
                    // Update last used timestamp
                    $tokenModel->last_used_at = now();
                    $tokenModel->save();
                    
                    return $next($request);
                }
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'Unauthenticated.',
        ], 401);
    }
}
