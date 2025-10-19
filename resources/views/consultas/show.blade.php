<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            {{ __('Detalhes da Consulta') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <a href="{{ route('consultas.index') }}" 
                           class="text-blue-600 hover:text-blue-800">← Voltar para Consultas</a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">
                                Informações da Consulta
                            </h3>
                            
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Especialidade:</dt>
                                    <dd class="text-sm text-gray-900">{{ $consulta->especialidade }}</dd>
                                </div>
                                
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Data e Hora:</dt>
                                    <dd class="text-sm text-gray-900">
                                        {{ \Carbon\Carbon::parse($consulta->data_hora)->format('d/m/Y H:i') }}
                                    </dd>
                                </div>
                                
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Local:</dt>
                                    <dd class="text-sm text-gray-900">{{ $consulta->local }}</dd>
                                </div>
                                
                                @if($consulta->observacoes)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Observações:</dt>
                                        <dd class="text-sm text-gray-900">{{ $consulta->observacoes }}</dd>
                                    </div>
                                @endif
                            </dl>
                        </div>

                        <div>
                            <h3 class="text-lg font-medium text-gray-900">
                                Ações
                            </h3>
                            
                            <div class="space-y-3">
                                <a href="{{ route('consultas.edit', $consulta) }}" 
                                   class="block w-full bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded text-center">
                                    Editar Consulta
                                </a>
                                
                                <form action="{{ route('consultas.destroy', $consulta) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="block w-full bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                                            onclick="return confirm('Tem certeza que deseja cancelar esta consulta?')">
                                        Cancelar Consulta
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
