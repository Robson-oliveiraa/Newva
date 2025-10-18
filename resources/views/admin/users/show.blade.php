<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Detalhes do Usuário') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="mb-6">
                        <a href="{{ route('admin.users.index') }}" 
                           class="text-blue-600 hover:text-blue-800">← Voltar para Usuários</a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                                Informações Pessoais
                            </h3>
                            
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nome:</dt>
                                    <dd class="text-sm text-gray-900 dark:text-gray-100">{{ $user->name }}</dd>
                                </div>
                                
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email:</dt>
                                    <dd class="text-sm text-gray-900 dark:text-gray-100">{{ $user->email }}</dd>
                                </div>
                                
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">CPF:</dt>
                                    <dd class="text-sm text-gray-900 dark:text-gray-100">{{ $user->cpf }}</dd>
                                </div>
                                
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Sexo:</dt>
                                    <dd class="text-sm text-gray-900 dark:text-gray-100">{{ $user->sexo }}</dd>
                                </div>
                                
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Idade:</dt>
                                    <dd class="text-sm text-gray-900 dark:text-gray-100">{{ $user->idade }} anos</dd>
                                </div>
                                
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Papel:</dt>
                                    <dd class="text-sm">
                                        @foreach($user->roles as $role)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $role->name === 'administrator' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800' }}">
                                                {{ ucfirst($role->name) }}
                                            </span>
                                        @endforeach
                                    </dd>
                                </div>
                            </dl>
                        </div>

                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                                Ações
                            </h3>
                            
                            <div class="space-y-3">
                                <a href="{{ route('admin.users.edit', $user) }}" 
                                   class="block w-full bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded text-center">
                                    Editar Usuário
                                </a>
                                
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="block w-full bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                                            onclick="return confirm('Tem certeza que deseja remover este usuário?')">
                                        Remover Usuário
                                    </button>
                                </form>
                            </div>

                            @if($user->consultas->count() > 0)
                                <div class="mt-6">
                                    <h4 class="text-md font-medium text-gray-900 dark:text-gray-100 mb-2">
                                        Consultas ({{ $user->consultas->count() }})
                                    </h4>
                                    <div class="space-y-2">
                                        @foreach($user->consultas->take(3) as $consulta)
                                            <div class="text-sm text-gray-600 dark:text-gray-400">
                                                {{ $consulta->especialidade }} - {{ \Carbon\Carbon::parse($consulta->data_hora)->format('d/m/Y') }}
                                            </div>
                                        @endforeach
                                        @if($user->consultas->count() > 3)
                                            <div class="text-sm text-gray-500">... e mais {{ $user->consultas->count() - 3 }} consultas</div>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            @if($user->vacinasAplicadas->count() > 0)
                                <div class="mt-6">
                                    <h4 class="text-md font-medium text-gray-900 dark:text-gray-100 mb-2">
                                        Vacinas ({{ $user->vacinasAplicadas->count() }})
                                    </h4>
                                    <div class="space-y-2">
                                        @foreach($user->vacinasAplicadas->take(3) as $vacina)
                                            <div class="text-sm text-gray-600 dark:text-gray-400">
                                                {{ $vacina->vacina->nome }} - {{ \Carbon\Carbon::parse($vacina->data_aplicacao)->format('d/m/Y') }}
                                            </div>
                                        @endforeach
                                        @if($user->vacinasAplicadas->count() > 3)
                                            <div class="text-sm text-gray-500">... e mais {{ $user->vacinasAplicadas->count() - 3 }} vacinas</div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
