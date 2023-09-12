<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;
use Exception;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        try {
            $this->validate($request,[
                'username' => 'required|string',
                'password' =>  'required|string'
            ]);

            $credentials = $request->only(['username','password']);

            if (!auth()->attempt($credentials)) {
                throw new Exception('Invalid credentials. Try again');
            }
            $user = auth()->user();

            $response = $this->loginSuccess($user);

        }
        catch(ValidationException $exception){
            $response = $this->validationError($exception);
        }
        catch (Throwable $error) {
            $response = $this->errorResponse($error);
        } finally {
            return $response;
        }
    }

    private function setLink(string $role) : string {
        switch($role){
            case 'doctor':
                $link = '/doctor/home';
                break;
            case 'patient':
                $link = '/patient/home';
                break;
            default:
                throw new Exception('Invalid role');
        }
        return $link;
    }

    protected function loginSuccess(User $user) : JsonResponse{
        return response()->json([
            'user' => [
                'username' => $user->username,
                'token' => $user->createToken($user->id.'_api')
            ],
            'link' => $this->setLink($user->role),
            'errorCaught' => false
        ]);
    }

    public function logout() : JsonResponse{
        try{
            $user = auth('sanctum')->user();

            foreach($user->tokens as $token){
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

    protected function validationError(ValidationException $exception){
        return response()->json([
            'validationError' => true,
            'messages' => $exception->errors(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTrace(),
        ]);
    }
}
