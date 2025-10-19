<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-lg sm:text-xl text-blue-900">
            {{ __('Minhas Consultas') }}
        </h2>
    </x-slot>

    <div class="py-8 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow-md sm:rounded-lg">
                <div class="p-4 sm:p-6 text-gray-900">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6 gap-4">
                        <h3 class="text-base sm:text-lg font-semibold text-gray-800">Suas Consultas Agendadas</h3>
                        <a href="{{ route('consultas.create') }}"
                            class="bg-blue-900 hover:bg-blue-800 text-white font-semibold py-2.5 px-4 rounded-md text-sm sm:text-base text-center transition-colors">
                            Agendar Nova Consulta
                        </a>
                    </div>

                    @if (session('success'))
                        <div
                            class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-md mb-4 text-sm sm:text-base">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($consultas->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-4 sm:px-6 py-3 text-left text-xs sm:text-sm font-medium text-gray-600 uppercase tracking-wider">
                                            Especialidade
                                        </th>
                                        <th
                                            class="px-4 sm:px-6 py-3 text-left text-xs sm:text-sm font-medium text-gray-600 uppercase tracking-wider">
                                            Data/Hora
                                        </th>
                                        <th
                                            class="px-4 sm:px-6 py-3 text-left text-xs sm:text-sm font-medium text-gray-600 uppercase tracking-wider">
                                            Local
                                        </th>
                                        <th
                                            class="px-4 sm:px-6 py-3 text-left text-xs sm:text-sm font-medium text-gray-600 uppercase tracking-wider">
                                            Ações
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($consultas as $consulta)
                                        <tr>
                                            <td
                                                class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm sm:text-base font-medium text-gray-900">
                                                {{ $consulta->especialidade }}
                                            </td>
                                            <td
                                                class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm sm:text-base text-gray-600">
                                                {{ \Carbon\Carbon::parse($consulta->data_hora)->format('d/m/Y H:i') }}
                                            </td>
                                            <td
                                                class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm sm:text-base text-gray-600">
                                                {{ $consulta->local }}
                                            </td>
                                            <td
                                                class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm sm:text-base font-medium">
                                                <a href="{{ route('consultas.show', $consulta) }}"
                                                    class="text-blue-600 hover:text-blue-800 mr-3 transition-colors">Ver</a>
                                                <a href="{{ route('consultas.edit', $consulta) }}"
                                                    class="text-blue-600 hover:text-blue-800 mr-3 transition-colors">Editar</a>
                                                <form action="{{ route('consultas.destroy', $consulta) }}"
                                                    method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="text-red-600 hover:text-red-800 transition-colors"
                                                        onclick="return confirm('Tem certeza que deseja cancelar esta consulta?')"
                                                        aria-label="Cancelar consulta">
                                                        Cancelar
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            {{ $consultas->links() }}
                        </div>
                    @else
                        <div class="text-center py-8">
                            <p class="text-gray-600 text-sm sm:text-base">Você ainda não possui consultas agendadas.</p>
                            <a href="{{ route('consultas.create') }}"
                                class="mt-4 inline-block bg-blue-900 hover:bg-blue-800 text-white font-semibold py-2.5 px-4 rounded-md text-sm sm:text-base transition-colors">
                                Agendar Primeira Consulta
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
