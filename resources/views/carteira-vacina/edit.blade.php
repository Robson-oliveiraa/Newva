<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-lg sm:text-xl text-blue-900">
            {{ __('Editar Registro de Vacina') }}
        </h2>
    </x-slot>

    <div class="py-8 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow-md sm:rounded-lg">
                <div class="p-4 sm:p-6 text-gray-900">
                    <div class="mb-6">
                        <a href="{{ route('carteira-vacina.show', $carteiraVacina) }}"
                            class="text-blue-600 hover:text-blue-800 text-sm sm:text-base font-medium transition-colors">
                            ← Voltar para Detalhes
                        </a>
                    </div>

                    <form method="POST" action="{{ route('carteira-vacina.update', $carteiraVacina) }}"
                        class="space-y-6 sm:space-y-5 max-w-lg mx-auto">
                        @csrf
                        @method('PUT')

                        <div>
                            <label for="vacina_id" class="block text-sm sm:text-base font-medium text-gray-700 mb-1.5">
                                Vacina
                            </label>
                            <select name="vacina_id" id="vacina_id"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm sm:text-base py-2.5 sm:py-3"
                                required aria-describedby="vacina_id-error">
                                <option value="">Selecione uma vacina</option>
                                @foreach ($vacinas as $vacina)
                                    <option value="{{ $vacina->id }}"
                                        {{ old('vacina_id', $carteiraVacina->vacina_id) == $vacina->id ? 'selected' : '' }}>
                                        {{ $vacina->nome }} - {{ $vacina->descricao }}
                                    </option>
                                @endforeach
                            </select>
                            @error('vacina_id')
                                <p class="mt-1 text-xs sm:text-sm text-red-600" id="vacina_id-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="data_aplicacao"
                                class="block text-sm sm:text-base font-medium text-gray-700 mb-1.5">
                                Data de Aplicação
                            </label>
                            <input type="date" name="data_aplicacao" id="data_aplicacao"
                                value="{{ old('data_aplicacao', $carteiraVacina->data_aplicacao) }}"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm sm:text-base py-2.5 sm:py-3"
                                required aria-describedby="data_aplicacao-error">
                            @error('data_aplicacao')
                                <p class="mt-1 text-xs sm:text-sm text-red-600" id="data_aplicacao-error">
                                    {{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="vencimento" class="block text-sm sm:text-base font-medium text-gray-700 mb-1.5">
                                Data de Vencimento (opcional)
                            </label>
                            <input type="date" name="vencimento" id="vencimento"
                                value="{{ old('vencimento', $carteiraVacina->vencimento) }}"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm sm:text-base py-2.5 sm:py-3"
                                aria-describedby="vencimento-error">
                            @error('vencimento')
                                <p class="mt-1 text-xs sm:text-sm text-red-600" id="vencimento-error">{{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-end gap-4 sm:gap-x-6">
                            <a href="{{ route('carteira-vacina.show', $carteiraVacina) }}"
                                class="bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2.5 px-4 rounded-md text-sm sm:text-base text-center transition-colors">
                                Cancelar
                            </a>
                            <button type="submit"
                                class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2.5 px-4 rounded-md text-sm sm:text-base transition-colors">
                                Atualizar Registro
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
