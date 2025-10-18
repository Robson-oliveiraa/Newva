<?php

namespace App\Http\Controllers;

use App\Models\Consulta;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ConsultaController extends Controller
{

    /**
     * Display a listing of the user's consultations
     */
    public function index(): View
    {
        $consultas = auth()->user()->consultas()->orderBy('data_hora', 'desc')->paginate(10);
        return view('consultas.index', compact('consultas'));
    }

    /**
     * Show the form for creating a new consultation
     */
    public function create(): View
    {
        return view('consultas.create');
    }

    /**
     * Store a newly created consultation
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'especialidade' => ['required', 'string', 'max:255'],
            'data_hora' => ['required', 'date', 'after:now'],
            'local' => ['required', 'string', 'max:255'],
            'observacoes' => ['nullable', 'string', 'max:1000'],
        ]);

        auth()->user()->consultas()->create([
            'especialidade' => $request->especialidade,
            'data_hora' => $request->data_hora,
            'local' => $request->local,
            'observacoes' => $request->observacoes,
        ]);

        return redirect()->route('consultas.index')
            ->with('success', 'Consulta agendada com sucesso!');
    }

    /**
     * Display the specified consultation
     */
    public function show(Consulta $consulta): View
    {
        // Ensure user can only see their own consultations
        if ($consulta->user_id !== auth()->id()) {
            abort(403);
        }

        return view('consultas.show', compact('consulta'));
    }

    /**
     * Show the form for editing the specified consultation
     */
    public function edit(Consulta $consulta): View
    {
        // Ensure user can only edit their own consultations
        if ($consulta->user_id !== auth()->id()) {
            abort(403);
        }

        return view('consultas.edit', compact('consulta'));
    }

    /**
     * Update the specified consultation
     */
    public function update(Request $request, Consulta $consulta): RedirectResponse
    {
        // Ensure user can only update their own consultations
        if ($consulta->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'especialidade' => ['required', 'string', 'max:255'],
            'data_hora' => ['required', 'date', 'after:now'],
            'local' => ['required', 'string', 'max:255'],
            'observacoes' => ['nullable', 'string', 'max:1000'],
        ]);

        $consulta->update([
            'especialidade' => $request->especialidade,
            'data_hora' => $request->data_hora,
            'local' => $request->local,
            'observacoes' => $request->observacoes,
        ]);

        return redirect()->route('consultas.index')
            ->with('success', 'Consulta atualizada com sucesso!');
    }

    /**
     * Remove the specified consultation
     */
    public function destroy(Consulta $consulta): RedirectResponse
    {
        // Ensure user can only delete their own consultations
        if ($consulta->user_id !== auth()->id()) {
            abort(403);
        }

        $consulta->delete();
        return redirect()->route('consultas.index')
            ->with('success', 'Consulta cancelada com sucesso!');
    }
}
