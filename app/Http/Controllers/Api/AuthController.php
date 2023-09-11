<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;
use Exception;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        try {
            $credentials = $request->only(['username', 'password', 'remember_me']);
            $credentials =  [
                    'username' => $credentials['username'],
                    'password' => $credentials['password']
            ];

            if (!Auth::attempt($credentials,$credentials['rememebr_me'])) {
                throw new Exception('Invalid credentials. Try again');
            }
            $user = Auth::user();

            $response = $this->loginSuccess($user);

        } catch (Throwable $error) {
            $response = $this->errorResponse($error);
        } finally {
            return $response;
        }
    }

    protected function loginSuccess(User $user){
        return response()->json([
            'user' => [
                'username' => $user->username,
                'token' => $user->createToken('api')
            ],
            'errorCaught' => false
        ]);
    }

    public function logout(){
        try{
            $user = Auth::user();

            $tokens = $user->tokens();

            foreach($tokens as $token){
                $token->delete();
            }

            $response = response()->json(
                [
                    'message' => 'Logged out',
                    'errorCaught' => false
                ]
            );
        }
        catch(Throwable $error){
            $response = $this->errorResponse($error);
        }
        finally{
            return $response;
        }
    }
}
