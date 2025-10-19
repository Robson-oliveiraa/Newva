<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-lg sm:text-xl text-blue-900">
            {{ __('Detalhes da Vacina') }}
        </h2>
    </x-slot>

    <div class="py-8 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow-md sm:rounded-lg">
                <div class="p-4 sm:p-6 text-gray-900">
                    <div class="mb-6">
                        <a href="{{ route('carteira-vacina.index') }}"
                            class="text-blue-600 hover:text-blue-800 text-sm sm:text-base font-medium transition-colors">
                            ← Voltar para Carteira
                        </a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                        <div>
                            <h3 class="text-base sm:text-lg font-semibold text-gray-800 mb-4">
                                Informações da Vacina
                            </h3>
                            <dl class="space-y-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-600">Nome da Vacina:</dt>
                                    <dd class="text-sm sm:text-base text-gray-900">{{ $carteiraVacina->vacina->nome }}
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-600">Descrição:</dt>
                                    <dd class="text-sm sm:text-base text-gray-900">
                                        {{ $carteiraVacina->vacina->descricao }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-600">Data de Aplicação:</dt>
                                    <dd class="text-sm sm:text-base text-gray-900">
                                        {{ \Carbon\Carbon::parse($carteiraVacina->data_aplicacao)->format('d/m/Y') }}
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-600">Data de Vencimento:</dt>
                                    <dd class="text-sm sm:text-base text-gray-900">
                                        @if ($carteiraVacina->vencimento)
                                            {{ \Carbon\Carbon::parse($carteiraVacina->vencimento)->format('d/m/Y') }}
                                        @else
                                            Não informado
                                        @endif
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-600">Status</dt>
                                    <dd class="text-sm sm:text-base">
                                        @if ($carteiraVacina->vencimento)
                                            @if (\Carbon\Carbon::parse($carteiraVacina->vencimento)->isPast())
                                                <span
                                                    class="px-2 py-1 inline-flex text-xs sm:text-sm font-semibold rounded-full bg-red-100 text-red-800">
                                                    Vencida há
                                                    {{ \Carbon\Carbon::parse($carteiraVacina->vencimento)->diffInDays(now()) }}
                                                    dias
                                                </span>
                                            @elseif(\Carbon\Carbon::parse($carteiraVacina->vencimento)->diffInDays(now()) <= 30)
                                                <span
                                                    class="px-2 py-1 inline-flex text-xs sm:text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    Vencendo em
                                                    {{ abs(\Carbon\Carbon::parse($carteiraVacina->vencimento)->diffInDays(now()->format("Y-m-d")))  }}
                                                    dias
                                                </span>
                                            @else
                                                <span
                                                    class="px-2 py-1 inline-flex text-xs sm:text-sm font-semibold rounded-full bg-green-100 text-green-800">
                                                    Válida 
                                                </span>
                                            @endif
                                        @else
                                            <span
                                                class="px-2 py-1 inline-flex text-xs sm:text-sm font-semibold rounded-full bg-green-100 text-green-800">
                                                Válida
                                            </span>
                                        @endif
                                    </dd>
                                </div>
                            </dl>
                        </div>

                        <div>
                            <h3 class="text-base sm:text-lg font-semibold text-gray-800 mb-4">
                                Ações
                            </h3>
                            <div class="space-y-3">
                                <a href="{{ route('carteira-vacina.edit', $carteiraVacina) }}"
                                    class="block w-full bg-blue-900 hover:bg-blue-800 text-white font-semibold py-2.5 px-4 rounded-md text-center text-sm sm:text-base transition-colors">
                                    Editar Registro
                                </a>
                                <form action="{{ route('carteira-vacina.destroy', $carteiraVacina) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="block w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-2.5 px-4 rounded-md text-sm sm:text-base transition-colors"
                                        onclick="return confirm('Tem certeza que deseja remover este registro?')"
                                        aria-label="Remover registro">
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
