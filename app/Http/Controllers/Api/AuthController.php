<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;
use Exception;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function currentUser(Request $request): JsonResponse
    {

        try {
            $requestedUser = $request->user();

            $authUser = auth()->user();

            if ($requestedUser->id === $authUser->id) {
                $response = response()->json([
                    'user' => [
                        'username' => $authUser->username,
                        'token' => $authUser->tokens->first()
                    ],
                    'link' => $this->setLink($authUser->role)
                ]);
            }
        } catch (Throwable $error) {
            $response = $this->errorResponse($error);
        } finally {
            return $response;
        }
    }
    public function login(Request $request): JsonResponse
    {
        try {
            $this->validate($request, [
                'username' => 'required|string',
                'password' =>  'required|string'
            ]);

            $credentials = $request->only(['username', 'password']);

            if (!auth()->attempt($credentials)) {
                throw new Exception('Invalid credentials. Try again');
            }
            $user = auth()->user();

            $response = $this->successResponse($user);
        } catch (ValidationException $exception) {
            $response = $this->validationError($exception);
        } catch (Throwable $error) {
            $response = $this->errorResponse($error);
        } finally {
            return $response;
        }
    }

    private function setLink(string $role): string
    {
        switch ($role) {
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

    protected function successResponse(User $user): JsonResponse
    {
        return response()->json([
            'user' => [
                'username' => $user->username,
                'token' => $user->createToken($user->id . '_api')->plainTextToken
            ],
            'link' => $this->setLink($user->role),
        ]);
    }

    public function logout(): JsonResponse
    {
        try {
            $user = auth('sanctum')->user();

            foreach ($user->tokens as $token) {
                $token->delete();
            }

            $response = response()->json(
                [
                    'message' => 'Logged out',
                ]
            );
        } catch (Throwable $error) {
            $response = $this->errorResponse($error);
        } finally {
            return $response;
        }
    }

    protected function validationError(ValidationException $exception)
    {
        return response()->json([
            'validationError' => true,
            'messages' => $exception->errors(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTrace(),
        ], 400);
    }

    public function registerUser(Request $request): JsonResponse
    {
        try{
            $rules = [
                'username' => ['required','string','unique:users,username','min:6','max:12'],
                'password' => ['required','string','min:6','max:12'],
                'role'  => ['required','string','in:doctor,patient'],
            ];

            $this->validate($request,$rules);

            $data = $request->only(['username','password','role']);

            $user = User::create($data);

            $response = $this->successResponse($user);

        }
        catch (ValidationException $exception) {
            $response = $this->validationError($exception);
        }
        catch(Throwable $error){
            $response = $this->errorResponse($error);
        }
        finally{
            return $response;
        }
    }
}
