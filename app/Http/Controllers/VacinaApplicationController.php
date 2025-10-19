<?php

namespace App\Http\Controllers;

use App\Models\CarteiraVacina;
use App\Models\Medico;
use App\Models\User;
use App\Models\Vacinas;
use Illuminate\Http\Request;
use Carbon\Carbon;

class VacinaApplicationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        
        if ($user->hasRole('medico')) {
            // Médico vê todas as aplicações que ele fez
            $aplicacoes = CarteiraVacina::with(['user', 'vacina'])
                ->where('medico_id', $user->medico->id)
                ->orderBy('data_aplicacao', 'desc')
                ->paginate(10);
        } else {
            // Usuário vê apenas suas próprias aplicações
            $aplicacoes = CarteiraVacina::with(['vacina', 'medico.user'])
                ->where('user_id', $user->id)
                ->orderBy('data_aplicacao', 'desc')
                ->paginate(10);
        }

        return view('carteira-vacina.index', compact('aplicacoes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $vacinas = Vacinas::all();
        $usuarios = User::whereDoesntHave('medico')->get();
        
        return view('carteira-vacina.create', compact('vacinas', 'usuarios'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'vacina_id' => 'required|exists:vacinas,id',
            'data_aplicacao' => 'required|date|before_or_equal:today',
        ]);

        $vacina = Vacinas::findOrFail($request->vacina_id);
        $dataAplicacao = Carbon::parse($request->data_aplicacao);
        $vencimento = $dataAplicacao->copy()->addMonths($vacina->validade_meses);

        // Verificar se o usuário logado é um médico
        $medicoId = null;
        if (auth()->user()->hasRole('medico') && auth()->user()->medico) {
            $medicoId = auth()->user()->medico->id;
        }

        $aplicacao = CarteiraVacina::create([
            'user_id' => $request->user_id,
            'vacina_id' => $request->vacina_id,
            'medico_id' => $medicoId,
            'data_aplicacao' => $dataAplicacao,
            'vencimento' => $vencimento,
        ]);

        return redirect()->route('carteira-vacina.index')
            ->with('success', 'Vacina aplicada com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(CarteiraVacina $carteiraVacina)
    {
        $carteiraVacina->load(['user', 'vacina', 'medico.user']);
        return view('carteira-vacina.show', compact('carteiraVacina'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CarteiraVacina $carteiraVacina)
    {
        $vacinas = Vacinas::all();
        $usuarios = User::whereDoesntHave('medico')->get();
        
        return view('carteira-vacina.edit', compact('carteiraVacina', 'vacinas', 'usuarios'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CarteiraVacina $carteiraVacina)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'vacina_id' => 'required|exists:vacinas,id',
            'data_aplicacao' => 'required|date|before_or_equal:today',
        ]);

        $vacina = Vacinas::findOrFail($request->vacina_id);
        $dataAplicacao = Carbon::parse($request->data_aplicacao);
        $vencimento = $dataAplicacao->copy()->addMonths($vacina->validade_meses);

        // Verificar se o usuário logado é um médico
        $medicoId = $carteiraVacina->medico_id; // Manter o médico original
        if (auth()->user()->hasRole('medico') && auth()->user()->medico) {
            $medicoId = auth()->user()->medico->id;
        }

        $carteiraVacina->update([
            'user_id' => $request->user_id,
            'vacina_id' => $request->vacina_id,
            'medico_id' => $medicoId,
            'data_aplicacao' => $dataAplicacao,
            'vencimento' => $vencimento,
        ]);

        return redirect()->route('carteira-vacina.index')
            ->with('success', 'Aplicação de vacina atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CarteiraVacina $carteiraVacina)
    {
        $carteiraVacina->delete();
        return redirect()->route('carteira-vacina.index')
            ->with('success', 'Aplicação de vacina removida com sucesso!');
    }
}
