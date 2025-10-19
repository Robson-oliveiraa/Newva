<?php

namespace App\Http\Controllers;

use App\Models\CarteiraVacina;
use App\Models\Consulta;
use App\Models\Medico;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }   

    public function index()
    {
        $user = auth()->user();
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // Dados para o calendário de vacinas
        $vacinasAplicadas = [];
        $vacinasVencendo = [];
        $consultas = [];
    
        // Inicializa as variáveis para evitar erro no compact()
        $totalUsuarios = null;
        $totalMedicos = null;
        $totalVacinasAplicadas = null;
        $totalConsultas = null;

        if ($user->hasRole('usuario')) {
            // Usuário vê suas próprias vacinas
            $vacinasAplicadas = CarteiraVacina::with(['vacina', 'medico.user'])
                ->where('user_id', $user->id)
                ->get()
                ->map(function ($vacina) {
                    return [
                        'id' => $vacina->id,
                        'title' => $vacina->vacina->nome,
                        'date' => $vacina->data_aplicacao->format('Y-m-d'),
                        'type' => 'aplicada',
                        'vencimento' => $vacina->vencimento->format('Y-m-d'),
                        'medico' => $vacina->medico->user->name ?? 'N/A'
                    ];
                });

            // Vacinas vencendo nos próximos 30 dias
            $vacinasVencendo = CarteiraVacina::with(['vacina', 'medico.user'])
                ->where('user_id', $user->id)
                ->whereBetween('vencimento', [
                    Carbon::now(),
                    Carbon::now()->addDays(30)
                ])
                ->get()
                ->map(function ($vacina) {
                    return [
                        'id' => $vacina->id,
                        'title' => $vacina->vacina->nome . ' (Vencendo)',
                        'date' => $vacina->vencimento->format('Y-m-d'),
                        'type' => 'vencendo',
                        'medico' => $vacina->medico->user->name ?? 'N/A'
                    ];
                });

            // Consultas do usuário
            $consultas = Consulta::with(['medico.user'])
                ->where('user_id', $user->id)
                ->whereMonth('data_hora', $currentMonth)
                ->whereYear('data_hora', $currentYear)
                ->get()
                ->map(function ($consulta) {
                    return [
                        'id' => $consulta->id,
                        'title' => $consulta->especialidade . ' - ' . ($consulta->medico->user->name ?? 'N/A'),
                        'date' => Carbon::parse($consulta->data_hora)->format('Y-m-d'),
                        'time' => Carbon::parse($consulta->data_hora)->format('H:i'),
                        'type' => 'consulta',
                        'status' => $consulta->status
                    ];
                });

        } elseif ($user->hasRole('medico')) {
            // Médico vê suas aplicações e consultas
            $medico = $user->medico;
            
            $vacinasAplicadas = CarteiraVacina::with(['vacina', 'user'])
                ->where('medico_id', $medico->id)
                ->get()
                ->map(function ($vacina) {
                    return [
                        'id' => $vacina->id,
                        'title' => $vacina->vacina->nome . ' - ' . $vacina->user->name,
                        'date' => $vacina->data_aplicacao->format('Y-m-d'),
                        'type' => 'aplicada',
                        'paciente' => $vacina->user->name
                    ];
                });

            $consultas = Consulta::with(['user'])
                ->where('medico_id', $medico->id)
                ->whereMonth('data_hora', $currentMonth)
                ->whereYear('data_hora', $currentYear)
                ->get()
                ->map(function ($consulta) {
                    return [
                        'id' => $consulta->id,
                        'title' => $consulta->especialidade . ' - ' . $consulta->user->name,
                        'date' => Carbon::parse($consulta->data_hora)->format('Y-m-d'),
                        'time' => Carbon::parse($consulta->data_hora)->format('H:i'),
                        'type' => 'consulta',
                        'status' => $consulta->status,
                        'paciente' => $consulta->user->name
                    ];
                });

        } elseif ($user->hasRole('administrator')) {
            // Admin vê estatísticas gerais
            $totalUsuarios = User::whereDoesntHave('medico')->count();
            $totalMedicos = Medico::where('ativo', true)->count();
            $totalVacinasAplicadas = CarteiraVacina::count();
            $totalConsultas = Consulta::count();

            $vacinasAplicadas = CarteiraVacina::with(['vacina', 'user', 'medico.user'])
                ->get()
                ->map(function ($vacina) {
                    return [
                        'id' => $vacina->id,
                        'title' => $vacina->vacina->nome . ' - ' . $vacina->user->name,
                        'date' => $vacina->data_aplicacao->format('Y-m-d'),
                        'type' => 'aplicada',
                        'medico' => $vacina->medico->user->name ?? 'N/A',
                        'paciente' => $vacina->user->name
                    ];
                });

            $consultas = Consulta::with(['user', 'medico.user'])
                ->whereMonth('data_hora', $currentMonth)
                ->whereYear('data_hora', $currentYear)
                ->get()
                ->map(function ($consulta) {
                    return [
                        'id' => $consulta->id,
                        'title' => $consulta->especialidade . ' - ' . $consulta->user->name,
                        'date' => Carbon::parse($consulta->data_hora)->format('Y-m-d'),
                        'time' => Carbon::parse($consulta->data_hora)->format('H:i'),
                        'type' => 'consulta',
                        'status' => $consulta->status,
                        'medico' => $consulta->medico->user->name ?? 'N/A',
                        'paciente' => $consulta->user->name
                    ];
                });
        }

        // Combinar todos os eventos para o calendário
        $eventos = collect($vacinasAplicadas)->merge($vacinasVencendo)->merge($consultas);

        return view('dashboard', compact('eventos', 'totalUsuarios', 'totalMedicos', 'totalVacinasAplicadas', 'totalConsultas'));
    }
}
