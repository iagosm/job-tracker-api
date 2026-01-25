<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEtapaRequest;
use App\Http\Requests\UpdateEtapaRequest;
use App\Models\Etapa;
use Symfony\Component\HttpFoundation\Response;


class EtapaController extends Controller
{
    public function create(StoreEtapaRequest $request)
    {
      try {
        $validate = $request->validated();
        $feedback = Etapa::create($validate);
        return $this->sendSuccess('Etapa criado com sucesso!', Response::HTTP_OK, $feedback);
      } catch (\Throwable $th) {
        return $this->sendError('Falha ao criar Etapa!', Response::HTTP_NOT_FOUND);
      }
    }

    public function update(UpdateEtapaRequest $request, $id)
    {
      try {
        $validate = $request->validated();
        $feedback = Etapa::findOrFail($id);
        $feedback->update($validate);
        return $this->sendSuccess('Etapa atualizado com sucesso', Response::HTTP_OK);
      } catch (\Throwable $th) {
        return $this->sendError('Etapa não encontrada!', Response::HTTP_NOT_FOUND);
      }
    }
    public function delete($id)
    {
      try {
        $feedback = Etapa::findOrFail($id);
        $feedback->delete();
        return $this->sendSuccess('Etapa deletado com sucesso', Response::HTTP_OK);
      } catch (\Throwable $th) {
        return $this->sendError('Etapa não encontrada!', Response::HTTP_NOT_FOUND);
      }
    }
}
