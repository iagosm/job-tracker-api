<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        try {
            $user = $request->user()->toArray();
            return $this->sendSuccess('', Response::HTTP_OK, $user);
        } catch (\Throwable $th) {
            return $this->sendError('Usuário não encontrado!', Response::HTTP_NOT_FOUND);
        }
    }

    public function update(ProfileRequest $request)
    {
        try {
            $user = $request->user();
            if (!$user->can('update', $user)) {
                return $this->sendError('Você não tem permissão para atualizar este usuário', Response::HTTP_FORBIDDEN);
            }
            $validated = $request->validated();
            if (!empty($validated['password'])) {
                $validated['password'] = Hash::make($validated['password']);
            } else {
                unset($validated['password']);
            }
            $user->update($validated);
            return $this->sendSuccess('Usuário atualizado com sucesso', Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->sendError('Usuário não encontrado!', Response::HTTP_NOT_FOUND);
        }
    }

    public function delete(Request $request)
    {
        try {
            $user = $request->user();
            if (!$user->can('delete', $user)) {
                return $this->sendError('Você não tem permissão para deletar este usuário', Response::HTTP_FORBIDDEN);
            }
            $user->tokens()->delete();
            $user->delete();
            return $this->sendSuccess('Usuário deletado com sucesso', Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->sendError('Usuário não encontrado!', Response::HTTP_NOT_FOUND);
        }
    }
}
