<?php

namespace App\Http\Controllers;

use App\Models\CarteiraVacina;
use App\Models\Vacinas;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class CarteiraVacinaController extends Controller
{

    /**
     * Display the user's vaccination card
     */
    public function index(): View
    {
        $vacinasAplicadas = auth()->user()->vacinasAplicadas()
            ->with('vacina')
            ->orderBy('data_aplicacao', 'desc')
            ->paginate(10);
        
        return view('carteira-vacina.index', compact('vacinasAplicadas'));
    }

    /**
     * Show the form for adding a new vaccination record
     */
    public function create(): View
    {
        $vacinas = Vacinas::all();
        return view('carteira-vacina.create', compact('vacinas'));
    }

    /**
     * Store a newly created vaccination record
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'vacina_id' => ['required', 'exists:vacinas,id'],
            'data_aplicacao' => ['required', 'date', 'before_or_equal:today'],
            'vencimento' => ['nullable', 'date', 'after:data_aplicacao'],
        ]);

        auth()->user()->vacinasAplicadas()->create([
            'vacina_id' => $request->vacina_id,
            'data_aplicacao' => $request->data_aplicacao,
            'vencimento' => $request->vencimento,
        ]);

        return redirect()->route('carteira-vacina.index')
            ->with('success', 'Vacina adicionada à carteira com sucesso!');
    }

    /**
     * Display the specified vaccination record
     */
    public function show(CarteiraVacina $carteiraVacina): View
    {
        // Ensure user can only see their own vaccination records
        if ($carteiraVacina->user_id !== auth()->id()) {
            abort(403);
        }

        $carteiraVacina->load('vacina');
        return view('carteira-vacina.show', compact('carteiraVacina'));
    }

    /**
     * Show the form for editing the specified vaccination record
     */
    public function edit(CarteiraVacina $carteiraVacina): View
    {
        // Ensure user can only edit their own vaccination records
        if ($carteiraVacina->user_id !== auth()->id()) {
            abort(403);
        }

        $vacinas = Vacinas::all();
        return view('carteira-vacina.edit', compact('carteiraVacina', 'vacinas'));
    }

    /**
     * Update the specified vaccination record
     */
    public function update(Request $request, CarteiraVacina $carteiraVacina): RedirectResponse
    {
        // Ensure user can only update their own vaccination records
        if ($carteiraVacina->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'vacina_id' => ['required', 'exists:vacinas,id'],
            'data_aplicacao' => ['required', 'date', 'before_or_equal:today'],
            'vencimento' => ['nullable', 'date', 'after:data_aplicacao'],
        ]);

        $carteiraVacina->update([
            'vacina_id' => $request->vacina_id,
            'data_aplicacao' => $request->data_aplicacao,
            'vencimento' => $request->vencimento,
        ]);

        return redirect()->route('carteira-vacina.index')
            ->with('success', 'Registro de vacina atualizado com sucesso!');
    }

    /**
     * Remove the specified vaccination record
     */
    public function destroy(CarteiraVacina $carteiraVacina): RedirectResponse
    {
        // Ensure user can only delete their own vaccination records
        if ($carteiraVacina->user_id !== auth()->id()) {
            abort(403);
        }

        $carteiraVacina->delete();
        return redirect()->route('carteira-vacina.index')
            ->with('success', 'Registro de vacina removido com sucesso!');
    }
}
