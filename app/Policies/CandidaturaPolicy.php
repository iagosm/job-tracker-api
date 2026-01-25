<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Candidatura;

class CandidaturaPolicy
{
    /**
     * Determina se o usuário pode visualizar qualquer candidatura.
     */
    public function viewAny(User $user): bool
    {
        // Todos usuários autenticados podem listar suas candidaturas
        return true;
    }

    /**
     * Determina se o usuário pode visualizar a candidatura específica.
     */
    public function view(User $user, Candidatura $candidatura): bool
    {
        // Usuário pode ver se for o dono ou admin
        return $user->id === $candidatura->user_id || $user->isAdmin();
    }

    /**
     * Determina se o usuário pode criar candidaturas.
     */
    public function create(User $user): bool
    {
        // Todos usuários autenticados podem criar candidaturas
        return true;
    }

    /**
     * Determina se o usuário pode atualizar a candidatura.
     */
    public function update(User $user, Candidatura $candidatura): bool
    {
        // Apenas o dono ou admin pode atualizar
        return $user->id === $candidatura->user_id || $user->isAdmin();
    }

    /**
     * Determina se o usuário pode deletar a candidatura.
     */
    public function delete(User $user, Candidatura $candidatura): bool
    {
        // Apenas o dono ou admin pode deletar
        return $user->id === $candidatura->user_id || $user->isAdmin();
    }

    /**
     * Determina se o usuário pode restaurar a candidatura.
     */
    public function restore(User $user, Candidatura $candidatura): bool
    {
        // Apenas o dono ou admin pode restaurar
        return $user->id === $candidatura->user_id || $user->isAdmin();
    }

    /**
     * Determina se o usuário pode deletar permanentemente a candidatura.
     */
    public function forceDelete(User $user, Candidatura $candidatura): bool
    {
        // Apenas admins podem deletar permanentemente
        return $user->isAdmin();
    }
}