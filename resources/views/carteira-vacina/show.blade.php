<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            {{ __('Detalhes da Vacina') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <a href="{{ route('carteira-vacina.index') }}" 
                           class="text-blue-600 hover:text-blue-800">← Voltar para Carteira</a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">
                                Informações da Vacina
                            </h3>
                            
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Nome da Vacina:</dt>
                                    <dd class="text-sm text-gray-900">{{ $carteiraVacina->vacina->nome }}</dd>
                                </div>
                                
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Descrição:</dt>
                                    <dd class="text-sm text-gray-900">{{ $carteiraVacina->vacina->descricao }}</dd>
                                </div>
                                
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Data de Aplicação:</dt>
                                    <dd class="text-sm text-gray-900">
                                        {{ \Carbon\Carbon::parse($carteiraVacina->data_aplicacao)->format('d/m/Y') }}
                                    </dd>
                                </div>
                                
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Data de Vencimento:</dt>
                                    <dd class="text-sm text-gray-900">
                                        @if($carteiraVacina->vencimento)
                                            {{ \Carbon\Carbon::parse($carteiraVacina->vencimento)->format('d/m/Y') }}
                                        @else
                                            Não informado
                                        @endif
                                    </dd>
                                </div>

                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Status:</dt>
                                    <dd class="text-sm">
                                        @if($carteiraVacina->vencimento && \Carbon\Carbon::parse($carteiraVacina->vencimento)->isPast())
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Vencida
                                            </span>
                                        @elseif($carteiraVacina->vencimento && \Carbon\Carbon::parse($carteiraVacina->vencimento)->diffInDays() <= 30)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                Vencendo em {{ \Carbon\Carbon::parse($carteiraVacina->vencimento)->diffInDays() }} dias
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Válida
                                            </span>
                                        @endif
                                    </dd>
                                </div>
                            </dl>
                        </div>

                        <div>
                            <h3 class="text-lg font-medium text-gray-900">
                                Ações
                            </h3>
                            
                            <div class="space-y-3">
                                <a href="{{ route('carteira-vacina.edit', $carteiraVacina) }}" 
                                   class="block w-full bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded text-center">
                                    Editar Registro
                                </a>
                                
                                <form action="{{ route('carteira-vacina.destroy', $carteiraVacina) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="block w-full bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                                            onclick="return confirm('Tem certeza que deseja remover este registro?')">
                                        Remover Registro
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
