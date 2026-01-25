<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(LoginRequest $request)
    {
        $data = $request->validated();
        if(Auth::attempt($data)) {
          $user = Auth::user();
          $token = $user->createToken("auth_token")->plainTextToken;
          $data = [ 
            'user' => UserResource::make($user),
            'token' => $token
          ];
          return $this->sendSuccess('Login Realizado com sucesso!', Response::HTTP_OK, $data);
        }
        return $this->sendError('As credenciais informadas são inválidas.', Response::HTTP_UNAUTHORIZED);
    }
}
