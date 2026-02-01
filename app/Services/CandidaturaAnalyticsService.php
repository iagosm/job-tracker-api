<?php

namespace App\Services;

use App\Models\Candidatura;
use App\Models\Feedback;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class CandidaturaAnalyticsService
{
    private int $cacheTTL = 7200; // 2 horas em segundos (analytics é mais pesado)

    /**
     * Retorna todas as análises detalhadas (com cache)
     */
    public function getAnalytics(int $userId): array
    {
        $key = "candidatura:analytics:$userId";

        return Cache::remember($key, $this->cacheTTL, function () use ($userId, $key) {
            return Cache::lock("lock:$key", 10)->block(5, function () use ($userId) {
                return $this->calculateAnalytics($userId);
            });
        });
    }

    /**
     * Calcula analytics sem cache (usado internamente)
     */
    private function calculateAnalytics(int $userId): array
    {
        return [
            'funil_conversao' => $this->getFunilConversao($userId),
            'por_status' => $this->getPorStatusDetalhado($userId),
            'timeline' => $this->getTimeline($userId, 6),
            'top_empresas' => $this->getTopEmpresas($userId),
            'efetividade_plataforma' => $this->getEfetividadePorPlataforma($userId),
            'por_dia_semana' => $this->getPorDiaSemana($userId),
            'tempo_por_etapa' => $this->getTempoPorEtapa($userId),
            'motivos_rejeicao' => $this->getMotivosRejeicao($userId),
            'performance_mensal' => $this->getPerformanceMensal($userId, 12),
        ];
    }

    /**
     * Limpa o cache de analytics do usuário
     */
    public function clearCache(int $userId): void
    {
        Cache::forget("candidatura:analytics:{$userId}");
    }

    private function getFunilConversao(int $userId): array
    {
        $total = Candidatura::where('user_id', $userId)->count();

        if ($total === 0) return [];

        $etapas = [
            'aplicado' => ['aplicado'],
            'em_analise' => ['em_analise', 'triagem'],
            'testes' => ['teste_tecnico'],
            'entrevistas' => ['entrevista_rh', 'entrevista_tecnica', 'entrevista_final'],
            'proposta' => ['proposta'],
            'contratado' => ['contratado'],
        ];

        $funil = [];
        foreach ($etapas as $etapa => $status) {
            $count = Candidatura::where('user_id', $userId)
                ->whereIn('status', $status)
                ->count();

            $funil[] = [
                'etapa' => $etapa,
                'label' => ucfirst(str_replace('_', ' ', $etapa)),
                'total' => $count,
                'percentual' => round(($count / $total) * 100, 2),
            ];
        }

        return $funil;
    }

    private function getPorStatusDetalhado(int $userId): array
    {
        return Candidatura::where('user_id', $userId)
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get()
            ->map(function($item) {
                return [
                    'status' => $item->status,
                    'label' => $this->getLabelStatus($item->status),
                    'total' => $item->total,
                ];
            })
            ->toArray();
    }

    private function getTimeline(int $userId, int $meses = 6): array
    {
        $dataInicio = Carbon::now()->subMonths($meses)->startOfMonth();

        return Candidatura::where('user_id', $userId)
            ->where('data_aplicacao', '>=', $dataInicio)
            ->selectRaw('DATE_FORMAT(data_aplicacao, "%Y-%m") as mes, count(*) as total')
            ->groupBy('mes')
            ->orderBy('mes')
            ->get()
            ->map(function($item) {
                return [
                    'mes' => Carbon::parse($item->mes . '-01')->format('M/Y'),
                    'total' => $item->total,
                ];
            })
            ->toArray();
    }

    private function getTopEmpresas(int $userId): array
    {
        return Candidatura::where('user_id', $userId)
            ->select('empresa', DB::raw('count(*) as total'))
            ->groupBy('empresa')
            ->orderByDesc('total')
            ->limit(10)
            ->get()
            ->toArray();
    }

    private function getEfetividadePorPlataforma(int $userId): array
    {
        return Candidatura::where('user_id', $userId)
            ->with('plataforma')
            ->get()
            ->groupBy('plataforma_id')
            ->map(function($candidaturas, $plataformaId) {
                $total = $candidaturas->count();
                $aprovadas = $candidaturas->whereIn('status', ['proposta', 'contratado'])->count();
                $entrevistas = $candidaturas->whereIn('status', [
                    'entrevista_rh',
                    'entrevista_tecnica',
                    'entrevista_final',
                    'proposta',
                    'contratado'
                ])->count();

                return [
                    'plataforma' => $candidaturas->first()->plataforma->nome ?? 'Não informado',
                    'total' => $total,
                    'entrevistas' => $entrevistas,
                    'aprovadas' => $aprovadas,
                    'taxa_entrevista' => $total > 0 ? round(($entrevistas / $total) * 100, 2) : 0,
                    'taxa_aprovacao' => $total > 0 ? round(($aprovadas / $total) * 100, 2) : 0,
                ];
            })
            ->values()
            ->sortByDesc('taxa_aprovacao')
            ->take(10)
            ->values()
            ->toArray();
    }

    private function getPorDiaSemana(int $userId): array
    {
        return Candidatura::where('user_id', $userId)
            ->selectRaw('DAYOFWEEK(data_aplicacao) as dia, count(*) as total')
            ->groupBy('dia')
            ->orderBy('dia')
            ->get()
            ->map(function($item) {
                return [
                    'dia' => $this->getDiaSemanaLabel($item->dia),
                    'total' => $item->total,
                ];
            })
            ->toArray();
    }

    private function getTempoPorEtapa(int $userId): array
    {
        $tempoResposta = Candidatura::where('user_id', $userId)
            ->whereNotNull('data_resposta')
            ->selectRaw('AVG(DATEDIFF(data_resposta, data_aplicacao)) as media')
            ->value('media');

        $tempoTotal = Candidatura::where('user_id', $userId)
            ->whereNotNull('data_encerramento')
            ->selectRaw('AVG(DATEDIFF(data_encerramento, data_aplicacao)) as media')
            ->value('media');

        return [
            [
                'etapa' => 'Tempo até primeira resposta',
                'dias_medio' => round($tempoResposta ?? 0, 1),
            ],
            [
                'etapa' => 'Tempo total do processo',
                'dias_medio' => round($tempoTotal ?? 0, 1),
            ],
        ];
    }

    private function getMotivosRejeicao(int $userId): array
    {
        return Feedback::whereHas('candidatura', function($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->whereNotNull('motivo_rejeicao')
            ->select('motivo_rejeicao', DB::raw('count(*) as total'))
            ->groupBy('motivo_rejeicao')
            ->orderByDesc('total')
            ->get()
            ->map(function($item) {
                return [
                    'motivo' => $item->motivo_rejeicao,
                    'label' => $this->getLabelMotivoRejeicao($item->motivo_rejeicao),
                    'total' => $item->total,
                ];
            })
            ->toArray();
    }

    private function getPerformanceMensal(int $userId, int $meses = 12): array
    {
        $dataInicio = Carbon::now()->subMonths($meses)->startOfMonth();

        $candidaturas = Candidatura::where('user_id', $userId)
            ->where('data_aplicacao', '>=', $dataInicio)
            ->get()
            ->groupBy(function($item) {
                return Carbon::parse($item->data_aplicacao)->format('Y-m');
            });

        $performance = [];
        for ($i = $meses - 1; $i >= 0; $i--) {
            $mes = Carbon::now()->subMonths($i)->format('Y-m');
            $mesLabel = Carbon::now()->subMonths($i)->format('M/Y');
            
            $candidaturasMes = $candidaturas->get($mes, collect());
            $total = $candidaturasMes->count();
            
            $performance[] = [
                'mes' => $mesLabel,
                'total_aplicadas' => $total,
                'em_processo' => $candidaturasMes->whereIn('status', ['em_analise', 'triagem'])->count(),
                'entrevistas' => $candidaturasMes->whereIn('status', [
                    'teste_tecnico',
                    'entrevista_rh',
                    'entrevista_tecnica',
                    'entrevista_final'
                ])->count(),
                'aprovadas' => $candidaturasMes->whereIn('status', ['proposta', 'contratado'])->count(),
                'rejeitadas' => $candidaturasMes->whereIn('status', ['rejeitado', 'desistiu'])->count(),
                'taxa_sucesso' => $total > 0
                    ? round(($candidaturasMes->whereIn('status', ['proposta', 'contratado'])->count() / $total) * 100, 2)
                    : 0,
            ];
        }

        return $performance;
    }

    // Métodos auxiliares para labels
    private function getLabelStatus(string $status): string
    {
        $labels = [
            'aplicado' => 'Aplicado',
            'em_analise' => 'Em Análise',
            'triagem' => 'Triagem',
            'teste_tecnico' => 'Teste Técnico',
            'entrevista_rh' => 'Entrevista RH',
            'entrevista_tecnica' => 'Entrevista Técnica',
            'entrevista_final' => 'Entrevista Final',
            'proposta' => 'Proposta',
            'contratado' => 'Contratado',
            'rejeitado' => 'Rejeitado',
            'desistiu' => 'Desistiu',
        ];

        return $labels[$status] ?? ucfirst($status);
    }

    private function getDiaSemanaLabel(int $dia): string
    {
        $dias = [
            1 => 'Domingo',
            2 => 'Segunda',
            3 => 'Terça',
            4 => 'Quarta',
            5 => 'Quinta',
            6 => 'Sexta',
            7 => 'Sábado',
        ];

        return $dias[$dia] ?? 'Desconhecido';
    }

    private function getLabelMotivoRejeicao(string $motivo): string
    {
        $labels = [
            'perfil_nao_adequado' => 'Perfil não adequado',
            'conhecimento_tecnico' => 'Conhecimento técnico',
            'experiencia_insuficiente' => 'Experiência insuficiente',
            'pretensao_salarial' => 'Pretensão salarial',
            'soft_skills' => 'Soft skills',
            'vaga_cancelada' => 'Vaga cancelada',
            'outro' => 'Outro',
        ];

        return $labels[$motivo] ?? ucfirst($motivo);
    }
}