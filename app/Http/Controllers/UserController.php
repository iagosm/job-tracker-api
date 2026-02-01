<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function index(Request $request)
    {
        try {
            $authUser = $request->user();

            if (!$authUser->can('viewAny', User::class)) {
                return $this->sendError(
                    'Você não tem permissão para listar usuários',
                    Response::HTTP_FORBIDDEN
                );
            }
            return $this->sendSuccess(
                'Usuários listados com sucesso',
                Response::HTTP_OK
            );
        } catch (\Throwable $th) {
            return $this->sendError(
                'Erro ao listar usuários',
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function store(Request $request)
    {
        try {
            $authUser = $request->user();

            if (!$authUser->can('create', User::class)) {
                return $this->sendError(
                    'Você não tem permissão para criar usuários',
                    Response::HTTP_FORBIDDEN
                );
            }

            $data = $request->all();
            $data['password'] = Hash::make($data['password']);

            $user = User::create($data);

            return $this->sendSuccess(
                'Usuário criado com sucesso',
                Response::HTTP_CREATED,
                $user
            );
        } catch (\Throwable $th) {
            return $this->sendError(
                'Erro ao criar usuário',
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    // public function show(Request $request, User $user)
    // {
    //     try {
    //         $authUser = $request->user();
    //         if (!$authUser->can('view', $user)) {
    //             return $this->sendError(
    //                 'Você não tem permissão para visualizar este usuário',
    //                 Response::HTTP_FORBIDDEN
    //             );
    //         }
    //         return $this->sendSuccess('', Response::HTTP_OK);
    //     } catch (\Throwable $th) {
    //         return $this->sendError(
    //             'Usuário não encontrado',
    //             Response::HTTP_NOT_FOUND
    //         );
    //     }
    // }

    public function update(Request $request, User $user)
    {
        try {
            $authUser = $request->user();

            if (!$authUser->can('update', $user)) {
                return $this->sendError(
                    'Você não tem permissão para atualizar este usuário',
                    Response::HTTP_FORBIDDEN
                );
            }

            $data = $request->all();

            if (!empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']);
            }

            $user->update($data);

            return $this->sendSuccess(
                'Usuário atualizado com sucesso',
                Response::HTTP_OK
            );
        } catch (\Throwable $th) {
            return $this->sendError(
                'Erro ao atualizar usuário',
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function destroy(Request $request, User $user)
    {
        try {
            $authUser = $request->user();

            if (!$authUser->can('delete', $user)) {
                return $this->sendError(
                    'Você não tem permissão para deletar este usuário',
                    Response::HTTP_FORBIDDEN
                );
            }

            $user->tokens()->delete();
            $user->delete();

            return $this->sendSuccess(
                'Usuário deletado com sucesso',
                Response::HTTP_OK
            );
        } catch (\Throwable $th) {
            return $this->sendError(
                'Erro ao deletar usuário',
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}