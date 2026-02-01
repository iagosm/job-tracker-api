<?php

namespace App\Services;

use App\Models\Candidatura;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class CandidaturaMetricsService
{
    private int $cacheTTL = 3600; // 1 hora em segundos

    /**
     * Calcula métricas básicas para o dashboard (com cache)
     */
    public function getMetrics(int $userId, string $periodo = '30'): array
    {
        $cacheKey = "candidatura:metrics:{$userId}:{$periodo}";
        $lockKey  = "lock:$cacheKey";

        return Cache::lock($lockKey, 10)->block(5, function () use ($cacheKey, $userId, $periodo) {
            return Cache::remember($cacheKey, $this->cacheTTL, function () use ($userId, $periodo) {
                return $this->calculateMetrics($userId, $periodo);
            });
        });
    }

    /**
     * Calcula as métricas sem cache (usado internamente)
     */
    private function calculateMetrics(int $userId, string $periodo): array
    {
        $query = Candidatura::where('user_id', $userId);
        
        // Aplica filtro de período
        if ($periodo !== 'all') {
            $query->where('data_aplicacao', '>=', Carbon::now()->subDays((int)$periodo));
        }

        $totalCandidaturas = $query->count();

        return [
            'total_candidaturas' => $totalCandidaturas,
            'candidaturas_mes' => $this->getCandidaturasMes($userId),
            'candidaturas_semana' => $this->getCandidaturasSemana($userId),
            'por_status' => $this->getCandidaturasPorStatus($userId, $periodo),
            'taxa_aprovacao' => $this->getTaxaAprovacao($userId, $periodo),
            'taxa_entrevista' => $this->getTaxaEntrevista($userId, $periodo),
            'tempo_medio_resposta' => $this->getTempoMedioResposta($userId, $periodo),
        ];
    }

    /**
     * Limpa o cache de métricas do usuário
     */
    public function clearCache(int $userId): void
    {
        $periodos = ['7', '30', '90', '365', 'all'];
        
        foreach ($periodos as $periodo) {
            Cache::forget("candidatura:metrics:{$userId}:{$periodo}");
        }
    }

    private function getCandidaturasMes(int $userId): int
    {
        return Candidatura::where('user_id', $userId)
            ->whereYear('data_aplicacao', Carbon::now()->year)
            ->whereMonth('data_aplicacao', Carbon::now()->month)
            ->count();
    }

    private function getCandidaturasSemana(int $userId): int
    {
        return Candidatura::where('user_id', $userId)
            ->whereBetween('data_aplicacao', [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek()
            ])
            ->count();
    }

    private function getCandidaturasPorStatus(int $userId, string $periodo): array
    {
        $porStatus = Candidatura::where('user_id', $userId)
            ->when($periodo !== 'all', function($q) use ($periodo) {
                $q->where('data_aplicacao', '>=', Carbon::now()->subDays((int)$periodo));
            })
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get()
            ->keyBy('status');

        $statusAplicadas = ['aplicado'];
        $statusEmProcesso = ['em_analise', 'triagem'];
        $statusEntrevistas = ['teste_tecnico', 'entrevista_rh', 'entrevista_tecnica', 'entrevista_final'];
        $statusAprovadas = ['proposta', 'contratado'];
        $statusRecusadas = ['rejeitado', 'desistiu'];

        return [
            'aplicadas' => $porStatus->whereIn('status', $statusAplicadas)->sum('total'),
            'em_processo' => $porStatus->whereIn('status', $statusEmProcesso)->sum('total'),
            'entrevistas' => $porStatus->whereIn('status', $statusEntrevistas)->sum('total'),
            'aprovadas' => $porStatus->whereIn('status', $statusAprovadas)->sum('total'),
            'recusadas' => $porStatus->whereIn('status', $statusRecusadas)->sum('total'),
        ];
    }

    private function getTaxaAprovacao(int $userId, string $periodo): float
    {
        $total = Candidatura::where('user_id', $userId)
            ->when($periodo !== 'all', function($q) use ($periodo) {
                $q->where('data_aplicacao', '>=', Carbon::now()->subDays((int)$periodo));
            })
            ->count();

        if ($total === 0) return 0;

        $aprovadas = Candidatura::where('user_id', $userId)
            ->when($periodo !== 'all', function($q) use ($periodo) {
                $q->where('data_aplicacao', '>=', Carbon::now()->subDays((int)$periodo));
            })
            ->whereIn('status', ['proposta', 'contratado'])
            ->count();

        return round(($aprovadas / $total) * 100, 2);
    }

    private function getTaxaEntrevista(int $userId, string $periodo): float
    {
        $total = Candidatura::where('user_id', $userId)
            ->when($periodo !== 'all', function($q) use ($periodo) {
                $q->where('data_aplicacao', '>=', Carbon::now()->subDays((int)$periodo));
            })
            ->count();

        if ($total === 0) return 0;

        $entrevistas = Candidatura::where('user_id', $userId)
            ->when($periodo !== 'all', function($q) use ($periodo) {
                $q->where('data_aplicacao', '>=', Carbon::now()->subDays((int)$periodo));
            })
            ->whereIn('status', [
                'teste_tecnico',
                'entrevista_rh',
                'entrevista_tecnica',
                'entrevista_final'
            ])
            ->count();

        return round(($entrevistas / $total) * 100, 2);
    }

    private function getTempoMedioResposta(int $userId, string $periodo): float
    {
        $media = Candidatura::where('user_id', $userId)
            ->whereNotNull('data_resposta')
            ->when($periodo !== 'all', function($q) use ($periodo) {
                $q->where('data_aplicacao', '>=', Carbon::now()->subDays((int)$periodo));
            })
            ->selectRaw('AVG(DATEDIFF(data_resposta, data_aplicacao)) as media')
            ->value('media');

        return round($media ?? 0, 1);
    }
}