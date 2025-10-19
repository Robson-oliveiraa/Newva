<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class DemasApiController extends Controller
{
    private $baseUrl = 'https://apidadosabertos.saude.gov.br/v1';

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Buscar unidades de saúde em Porto Velho - RO
     */
    public function getUnidadesPortoVelho()
    {
        try {
            $cacheKey = 'demas_unidades_porto_velho';
            
            $data = Cache::remember($cacheKey, 3600, function () {
                $response = Http::timeout(30)->get($this->baseUrl . '/vacinacao/sistema-de-informacao-de-insumos-estrategicos', [
                    'uf' => 'RO',
                    'municipio' => 'Porto Velho'
                ]);

                if ($response->successful()) {
                    return $response->json();
                }
                
                return null;
            });

            if ($data) {
                return response()->json([
                    'success' => true,
                    'data' => $data,
                    'message' => 'Dados das unidades de Porto Velho carregados com sucesso!'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Não foi possível carregar os dados das unidades de Porto Velho.'
            ], 500);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao conectar com a API do DEMAS: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Buscar vacinas distribuídas para Porto Velho
     */
    public function getVacinasDistribuidas()
    {
        try {
            $cacheKey = 'demas_vacinas_porto_velho';
            
            $data = Cache::remember($cacheKey, 1800, function () {
                $response = Http::timeout(30)->get($this->baseUrl . '/vacinacao/sistema-de-informacao-de-insumos-estrategicos', [
                    'uf' => 'RO',
                    'municipio' => 'Porto Velho',
                    'tipo_insumo' => 'vacina'
                ]);

                if ($response->successful()) {
                    return $response->json();
                }
                
                return null;
            });

            if ($data) {
                return response()->json([
                    'success' => true,
                    'data' => $data,
                    'message' => 'Dados das vacinas distribuídas em Porto Velho carregados com sucesso!'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Não foi possível carregar os dados das vacinas distribuídas.'
            ], 500);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao conectar com a API do DEMAS: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Buscar dados gerais de vacinação em Rondônia
     */
    public function getDadosRondonia()
    {
        try {
            $cacheKey = 'demas_dados_rondonia';
            
            $data = Cache::remember($cacheKey, 3600, function () {
                $response = Http::timeout(30)->get($this->baseUrl . '/vacinacao/sistema-de-informacao-de-insumos-estrategicos', [
                    'uf' => 'RO'
                ]);

                if ($response->successful()) {
                    return $response->json();
                }
                
                return null;
            });

            if ($data) {
                return response()->json([
                    'success' => true,
                    'data' => $data,
                    'message' => 'Dados de vacinação de Rondônia carregados com sucesso!'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Não foi possível carregar os dados de Rondônia.'
            ], 500);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao conectar com a API do DEMAS: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Dashboard com dados integrados do DEMAS
     */
    public function dashboard()
    {
        try {
            $unidades = $this->getUnidadesPortoVelho();
            $vacinas = $this->getVacinasDistribuidas();
            $dadosRondonia = $this->getDadosRondonia();

            return view('admin.demas-dashboard', compact('unidades', 'vacinas', 'dadosRondonia'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erro ao carregar dados do DEMAS: ' . $e->getMessage());
        }
    }

    /**
     * Limpar cache dos dados do DEMAS
     */
    public function clearCache()
    {
        Cache::forget('demas_unidades_porto_velho');
        Cache::forget('demas_vacinas_porto_velho');
        Cache::forget('demas_dados_rondonia');

        return response()->json([
            'success' => true,
            'message' => 'Cache limpo com sucesso!'
        ]);
    }
}
