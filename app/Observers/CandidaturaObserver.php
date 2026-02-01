<?php

namespace App\Observers;

use App\Models\Candidatura;
use App\Services\CandidaturaMetricsService;
use App\Services\CandidaturaAnalyticsService;

class CandidaturaObserver
{
    /**
     * Limpa o cache quando uma candidatura é criada
     */
    public function created(Candidatura $candidatura): void
    {
        $this->clearUserCache($candidatura->user_id);
    }

    /**
     * Limpa o cache quando uma candidatura é atualizada
     */
    public function updated(Candidatura $candidatura): void
    {
        app(CandidaturaMetricsService::class)->clearCache($candidatura->user_id);
        if ($candidatura->wasChanged(['status', 'data_aplicacao', 'data_resposta', 'data_encerramento'])) {
            $this->clearUserCache($candidatura->user_id);
        }
    }

    /**
     * Limpa o cache quando uma candidatura é deletada
     */
    public function deleted(Candidatura $candidatura): void
    {
        $this->clearUserCache($candidatura->user_id);
    }

    /**
     * Limpa todo o cache do usuário (metrics e analytics)
     */
    private function clearUserCache(int $userId): void
    {
        $metricsService = app(CandidaturaMetricsService::class);
        $analyticsService = app(CandidaturaAnalyticsService::class);
        
        $metricsService->clearCache($userId);
        $analyticsService->clearCache($userId);
    }
}