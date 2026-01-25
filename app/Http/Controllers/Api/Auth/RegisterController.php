<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;

class RegisterController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(RegisterRequest $request)
    {
        $validated = $request->validated();
        $user = User::create($validated);
        $token = $user->createToken('auth_token')->plainTextToken;
        $data = [
          'user'=> UserResource::make($user),
          'token'=> $token,
        ];
        return $this->sendSuccess('Registro realizado com sucesso!', Response::HTTP_OK, $data);
    }
}
