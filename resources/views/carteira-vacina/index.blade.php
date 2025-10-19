<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-lg sm:text-xl text-blue-900">
            {{ __('Minha Carteira de Vacina') }}
        </h2>
    </x-slot>

    <div class="py-8 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow-md sm:rounded-lg">
                <div class="p-4 sm:p-6 text-gray-900">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6 gap-4">
                        <h3 class="text-base sm:text-lg font-semibold text-gray-800">Suas Vacinas Aplicadas</h3>
                        <a href="{{ route('carteira-vacina.create') }}" 
                           class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2.5 px-4 rounded-md text-sm sm:text-base text-center transition-colors">
                            Adicionar Vacina
                        </a>
                    </div>

                    @if(session('success'))
                        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-md mb-4 text-sm sm:text-base">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($vacinasAplicadas->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 sm:px-6 py-3 text-left text-xs sm:text-sm font-medium text-gray-600 uppercase tracking-wider">
                                            Vacina
                                        </th>
                                        <th class="px-4 sm:px-6 py-3 text-left text-xs sm:text-sm font-medium text-gray-600 uppercase tracking-wider">
                                            Data de Aplicação
                                        </th>
                                        <th class="px-4 sm:px-6 py-3 text-left text-xs sm:text-sm font-medium text-gray-600 uppercase tracking-wider">
                                            Vencimento
                                        </th>
                                        <th class="px-4 sm:px-6 py-3 text-left text-xs sm:text-sm font-medium text-gray-600 uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th class="px-4 sm:px-6 py-3 text-left text-xs sm:text-sm font-medium text-gray-600 uppercase tracking-wider">
                                            Ações
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($vacinasAplicadas as $vacina)
                                        <tr>
                                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm sm:text-base font-medium text-gray-900">{{ $vacina->vacina->nome }}</div>
                                                <div class="text-sm text-gray-600">{{ $vacina->vacina->descricao }}</div>
                                            </td>
                                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm sm:text-base text-gray-600">
                                                {{ \Carbon\Carbon::parse($vacina->data_aplicacao)->format('d/m/Y') }}
                                            </td>
                                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm sm:text-base text-gray-600">
                                                @if($vacina->vencimento)
                                                    {{ \Carbon\Carbon::parse($vacina->vencimento)->format('Y-m-d') }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                                @if($vacina->vencimento && \Carbon\Carbon::parse($vacina->vencimento)->isPast())
                                                    <span class="px-2 py-1 inline-flex text-xs sm:text-sm font-semibold rounded-full bg-red-100 text-red-800">
                                                        Vencida
                                                    </span>
                                                @elseif($vacina->vencimento && \Carbon\Carbon::parse($vacina->vencimento)->diffInDays() <= 30)
                                                    <span class="px-2 py-1 inline-flex text-xs sm:text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                        Vencendo
                                                    </span>
                                                @else
                                                    <span class="px-2 py-1 inline-flex text-xs sm:text-sm font-semibold rounded-full bg-green-100 text-green-800">
                                                        Válida
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm sm:text-base font-medium">
                                                <a href="{{ route('carteira-vacina.show', $vacina) }}" 
                                                   class="text-blue-600 hover:text-blue-800 mr-3 transition-colors">Ver</a>
                                                <a href="{{ route('carteira-vacina.edit', $vacina) }}" 
                                                   class="text-blue-600 hover:text-blue-800 mr-3 transition-colors">Editar</a>
                                                <form action="{{ route('carteira-vacina.destroy', $vacina) }}" 
                                                      method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="text-red-600 hover:text-red-800 transition-colors"
                                                            onclick="return confirm('Tem certeza que deseja remover este registro?')"
                                                            aria-label="Remover registro">
                                                        Remover
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            {{ $vacinasAplicadas->links() }}
                        </div>
                    @else
                        <div class="text-center py-8">
                            <p class="text-gray-600 text-sm sm:text-base">Você ainda não possui vacinas registradas em sua carteira.</p>
                            <a href="{{ route('carteira-vacina.create') }}" 
                               class="mt-4 inline-block bg-green-600 hover:bg-green-700 text-white font-semibold py-2.5 px-4 rounded-md text-sm sm:text-base transition-colors">
                                Adicionar Primeira Vacina
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>