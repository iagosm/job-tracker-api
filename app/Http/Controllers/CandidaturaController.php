<?php

namespace App\Http\Controllers;

use App\Http\Requests\CandidaturaFilterRequest;
use App\Http\Requests\StoreCandidaturaRequest;
use App\Models\Candidatura;
use App\Services\CandidaturaAnalyticsService;
use App\Services\CandidaturaMetricsService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CandidaturaController extends Controller
{
    public function getAll(CandidaturaFilterRequest $request)
    {
        $perPage = $request->get('per_page', 10);
        $candidaturas = Candidatura::query()
            ->filter($request->validated())
            ->paginate($perPage)->toArray();
        return $this->sendSuccess('', Response::HTTP_OK, $candidaturas);
    }

    /**
     * Métricas básicas do dashboard (carrega rápido na home).
     * Uso: Cards principais da página inicial
     */
    public function metrics(Request $request, CandidaturaMetricsService $metricsService)
    {
        $userId = $request->user()->id;
        $periodo = $request->input('periodo', '30');
        
        return response()->json([
            'success' => true,
            'data' => $metricsService->getMetrics($userId, $periodo),
            'periodo' => $periodo,
        ]);
    }

    /**
     * Análises detalhadas e gráficos (página completa de analytics).
     * Uso: Página dedicada de análises e insights
     */
    public function analytics(Request $request, CandidaturaAnalyticsService $analyticsService)
    {
        return response()->json([
            'success' => true,
            'data' => $analyticsService->getAnalytics($request->user()->id),
        ]);
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
