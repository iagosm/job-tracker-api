<?php

namespace App\Http\Controllers;

use App\Http\Requests\PlataformaRequest;
use App\Models\Plataforma;
use Symfony\Component\HttpFoundation\Response;

class PlataformaController extends Controller
{
    public function getAll()
    {
        $tecnologia = Plataforma::all()->toArray();
        return $this->sendSuccess('', Response::HTTP_CREATED, $tecnologia);
    }

    public function create(PlataformaRequest $request)
    {
        try {
            $validate = $request->validated();
            $tecnologia = Plataforma::create($validate)->toArray();
            return $this->sendSuccess('Plataforma criada com sucesso!', Response::HTTP_CREATED, $tecnologia);
        } catch (\Throwable $th) {
            return $this->sendError('Falha ao criar plataforma!', Response::HTTP_NOT_FOUND);
        }
    }

    public function update(PlataformaRequest $request, $id)
    {
        try {
            $validate = $request->validated();
            $plataforma = Plataforma::findOrFail($id);
            $plataforma->update($validate);

            return $this->sendSuccess('Plataforma atualizada com sucesso!', Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->sendError('Plataforma não encontrada!', Response::HTTP_NOT_FOUND);
        }
    }

    public function delete($id)
    {
        try {
            $plataforma = Plataforma::findOrFail($id);
            $plataforma->delete();
            return $this->sendSuccess('Plataforma excluída com sucesso!', Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->sendError('Plataforma não encontrada!', Response::HTTP_NOT_FOUND);
        }
    }
}
