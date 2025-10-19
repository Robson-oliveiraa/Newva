<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            {{ __('Meu Perfil') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium">Informações Pessoais</h3>
                        <a href="{{ route('profile.edit') }}" 
                           class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Editar Perfil
                        </a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Informações Pessoais -->
                        <div>
                            <h4 class="text-md font-semibold text-gray-800">
                                Dados Pessoais
                            </h4>
                            
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Nome:</dt>
                                    <dd class="text-sm text-gray-900">{{ $user->name }}</dd>
                                </div>
                                
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Email:</dt>
                                    <dd class="text-sm text-gray-900">{{ $user->email }}</dd>
                                </div>
                                
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">CPF:</dt>
                                    <dd class="text-sm text-gray-900">{{ $user->cpf }}</dd>
                                </div>
                                
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Sexo:</dt>
                                    <dd class="text-sm text-gray-900">{{ $user->sexo }}</dd>
                                </div>
                                
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Idade:</dt>
                                    <dd class="text-sm text-gray-900">{{ $user->idade }} anos</dd>
                                </div>
                                
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Papel:</dt>
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

                        <!-- Estatísticas -->
                        <div>
                            <h4 class="text-md font-semibold text-gray-800">
                                Estatísticas
                            </h4>
                            
                            <div class="space-y-4">
                                <div class="bg-blue-50">
                                    <h5 class="font-semibold text-blue-800">Consultas</h5>
                                    <p class="text-2xl font-bold text-blue-600">
                                        {{ $user->consultas->count() }}
                                    </p>
                                    <p class="text-sm text-blue-600">consultas agendadas</p>
                                </div>
                                
                                <div class="bg-green-50">
                                    <h5 class="font-semibold text-green-800">Vacinas</h5>
                                    <p class="text-2xl font-bold text-green-600">
                                        {{ $user->vacinasAplicadas->count() }}
                                    </p>
                                    <p class="text-sm text-green-600">vacinas aplicadas</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Histórico Recente -->
                    @if($user->consultas->count() > 0 || $user->vacinasAplicadas->count() > 0)
                        <div class="mt-8">
                            <h4 class="text-md font-semibold text-gray-800">
                                Histórico Recente
                            </h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @if($user->consultas->count() > 0)
                                    <div>
                                        <h5 class="font-medium text-gray-800">
                                            Últimas Consultas
                                        </h5>
                                        <div class="space-y-2">
                                            @foreach($user->consultas->take(3) as $consulta)
                                                <div class="bg-gray-50">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $consulta->especialidade }}
                                                    </div>
                                                    <div class="text-xs text-gray-500">
                                                        {{ \Carbon\Carbon::parse($consulta->data_hora)->format('d/m/Y H:i') }}
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                @if($user->vacinasAplicadas->count() > 0)
                                    <div>
                                        <h5 class="font-medium text-gray-800">
                                            Últimas Vacinas
                                        </h5>
                                        <div class="space-y-2">
                                            @foreach($user->vacinasAplicadas->take(3) as $vacina)
                                                <div class="bg-gray-50">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $vacina->vacina->nome }}
                                                    </div>
                                                    <div class="text-xs text-gray-500">
                                                        {{ \Carbon\Carbon::parse($vacina->data_aplicacao)->format('d/m/Y') }}
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


