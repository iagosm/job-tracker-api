<?php

namespace App\Http\Controllers;

use App\Http\Requests\TecnologiaRequest;
use App\Models\Tecnologia;
use Illuminate\Http\Response;

class TecnologiaController extends Controller
{
    public function getAll()
    {
        $tecnologia = Tecnologia::all()->toArray();
        return $this->sendSuccess('', Response::HTTP_CREATED, $tecnologia);
    }

    public function create(TecnologiaRequest $request)
    {
        try {
            $validate = $request->validated();
            $tecnologia = Tecnologia::create($validate)->toArray();

            return $this->sendSuccess('Tecnologia criada com sucesso!', Response::HTTP_CREATED, $tecnologia);
        } catch (\Throwable $th) {
            return $this->sendError('Tecnologia não encontrada!', Response::HTTP_NOT_FOUND);
        }
    }

    public function update(TecnologiaRequest $request, $id)
    {
        try {
            $validate = $request->validated();
            $tecnologia = Tecnologia::findOrFail($id);
            $tecnologia->update($validate);

            return $this->sendSuccess('Tecnologia atualizada com sucesso!', Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->sendError('Tecnologia não encontrada!', Response::HTTP_NOT_FOUND);
        }
    }

    public function delete($id)
    {
        try {
            $tecnologia = Tecnologia::findOrFail($id);
            $tecnologia->delete();

            return $this->sendSuccess('Candidatura excluída com sucesso!', Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->sendError('Candidatura não encontrada!', Response::HTTP_NOT_FOUND);
        }
    }
}
