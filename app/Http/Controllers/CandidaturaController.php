<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCandidaturaRequest;
use App\Models\Candidatura;
use Symfony\Component\HttpFoundation\Response;

class CandidaturaController extends Controller
{
    public function getAll()
    {
        $candidaturas = Candidatura::all()->toArray();
        return $this->sendSuccess('', Response::HTTP_OK, $candidaturas);
    }

    public function metrics()
    {
      
    }

    public function analytics()
    {
      
    }

    public function create(StoreCandidaturaRequest $request)
    {
        try {
            $validated = $request->validated();
            $validated['user_id'] = auth()->id();
            $candidatura = Candidatura::create($validated)->toArray();

            return $this->sendSuccess('Candidatura realizada com sucesso!', Response::HTTP_CREATED, $candidatura);
        } catch (\Exception $e) {
            return $this->sendError('Erro ao criar candidatura', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(StoreCandidaturaRequest $request, $id)
    {
        try {
            $validated = $request->validated();
            $candidatura = Candidatura::findOrFail($id);
            $candidatura->update($validated);

            return $this->sendSuccess('Candidatura atualizada com sucesso!', Response::HTTP_OK, $candidatura->toArray());

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->sendError('Candidatura não encontrada!', Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return $this->sendError('Erro ao atualizar candidatura', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function delete($id)
    {
        try {
            $candidatura = Candidatura::findOrFail($id);
            $candidatura->delete();

            return $this->sendSuccess('Candidatura excluída com sucesso!', Response::HTTP_OK);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->sendError('Candidatura não encontrada!', Response::HTTP_NOT_FOUND);
        }
    }
}
