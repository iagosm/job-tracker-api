<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFeedbackRequest;
use App\Http\Requests\UpdateFeedbackRequest;
use App\Models\Feedback;
use Symfony\Component\HttpFoundation\Response;

class FeedbackController extends Controller
{
    public function create(StoreFeedbackRequest $request)
    {
      try {
        $validate = $request->validated();
        $feedback = Feedback::create($validate);
        return $this->sendSuccess('Feedback criado com sucesso!', Response::HTTP_OK, $feedback);
      } catch (\Throwable $th) {
        return $this->sendError('Falha ao criar Feedback!', Response::HTTP_NOT_FOUND);
      }
    }

    public function update(UpdateFeedbackRequest $request, $id)
    {
      try {
        $validate = $request->validated();
        $feedback = Feedback::findOrFail($id);
        $feedback->update($validate);
        return $this->sendSuccess('Feedback atualizado com sucesso', Response::HTTP_OK);
      } catch (\Throwable $th) {
        return $this->sendError('Feedback não encontrada!', Response::HTTP_NOT_FOUND);
      }
    }
    public function delete($id)
    {
      try {
        $feedback = Feedback::findOrFail($id);
        $feedback->delete();
        return $this->sendSuccess('Feedback deletado com sucesso', Response::HTTP_OK);
      } catch (\Throwable $th) {
        return $this->sendError('Feedback não encontrada!', Response::HTTP_NOT_FOUND);
      }
    }
}