<?php

namespace App\Http\Controllers;

use App\Models\Medico;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class MedicoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:medicos.read')->only(['index', 'show']);
        $this->middleware('permission:medicos.create')->only(['create', 'store']);
        $this->middleware('permission:medicos.update')->only(['edit', 'update']);
        $this->middleware('permission:medicos.delete')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $medicos = Medico::with('user')->paginate(10);
        return view('admin.medicos.index', compact('medicos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.medicos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'cpf' => 'required|string|max:14|unique:users',
            'sexo' => 'required|in:M,F,Outro',
            'idade' => 'required|integer|min:18|max:100',
            'crm' => 'required|string|max:20|unique:medicos',
            'especialidade' => 'required|string|max:255',
            'telefone' => 'nullable|string|max:20',
            'endereco' => 'nullable|string|max:500',
        ]);

        // Criar usuário
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'cpf' => $request->cpf,
            'sexo' => $request->sexo,
            'idade' => $request->idade,
        ]);

        // Criar médico
        $medico = Medico::create([
            'user_id' => $user->id,
            'crm' => $request->crm,
            'especialidade' => $request->especialidade,
            'telefone' => $request->telefone,
            'endereco' => $request->endereco,
        ]);

        // Atribuir role de médico
        $user->addRole('medico');

        return redirect()->route('medicos.index')
            ->with('success', 'Médico cadastrado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Medico $medico)
    {
        $medico->load('user');
        return view('admin.medicos.show', compact('medico'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Medico $medico)
    {
        $medico->load('user');
        return view('admin.medicos.edit', compact('medico'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Medico $medico)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($medico->user_id)],
            'cpf' => ['required', 'string', 'max:14', Rule::unique('users')->ignore($medico->user_id)],
            'sexo' => 'required|in:masculino,feminino,outro',
            'idade' => 'required|integer|min:18|max:100',
            'crm' => ['required', 'string', 'max:20', Rule::unique('medicos')->ignore($medico->id)],
            'especialidade' => 'required|string|max:255',
            'telefone' => 'nullable|string|max:20',
            'endereco' => 'nullable|string|max:500',
            'ativo' => 'boolean',
        ]);

        // Atualizar usuário
        $medico->user->update([
            'name' => $request->name,
            'email' => $request->email,
            'cpf' => $request->cpf,
            'sexo' => $request->sexo,
            'idade' => $request->idade,
        ]);

        // Atualizar médico
        $medico->update([
            'crm' => $request->crm,
            'especialidade' => $request->especialidade,
            'telefone' => $request->telefone,
            'endereco' => $request->endereco,
            'ativo' => $request->has('ativo'),
        ]);

        return redirect()->route('medicos.index')
            ->with('success', 'Médico atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Medico $medico)
    {
        $medico->user->delete(); // Isso também deletará o médico devido à foreign key
        return redirect()->route('medicos.index')
            ->with('success', 'Médico removido com sucesso!');
    }
}
