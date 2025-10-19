<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Cadastrar Novo Médico') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('admin.medicos.store') }}" method="POST">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Dados Pessoais -->
                            <div class="space-y-4">
                                <h3 class="text-lg font-semibold text-gray-900">Dados Pessoais</h3>
                                
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700">Nome Completo</label>
                                    <input type="text" name="name" id="name" value="{{ old('name') }}" 
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-300 @enderror" required>
                                    @error('name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                    <input type="email" name="email" id="email" value="{{ old('email') }}" 
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-300 @enderror" required>
                                    @error('email')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="password" class="block text-sm font-medium text-gray-700">Senha</label>
                                    <input type="password" name="password" id="password" 
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('password') border-red-300 @enderror" required>
                                    @error('password')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmar Senha</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation" 
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                                </div>

                                <div>
                                    <label for="cpf" class="block text-sm font-medium text-gray-700">CPF</label>
                                    <input type="text" name="cpf" id="cpf" value="{{ old('cpf') }}" 
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('cpf') border-red-300 @enderror" required>
                                    @error('cpf')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="sexo" class="block text-sm font-medium text-gray-700">Sexo</label>
                                    <select name="sexo" id="sexo" 
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('sexo') border-red-300 @enderror" required>
                                        <option value="">Selecione...</option>
                                        <option value="M" {{ old('sexo') == 'masculino' ? 'selected' : '' }}>Masculino</option>
                                        <option value="F" {{ old('sexo') == 'feminino' ? 'selected' : '' }}>Feminino</option>
                                        <option value="Outro" {{ old('sexo') == 'outro' ? 'selected' : '' }}>Outro</option>
                                    </select>
                                    @error('sexo')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="idade" class="block text-sm font-medium text-gray-700">Idade</label>
                                    <input type="number" name="idade" id="idade" value="{{ old('idade') }}" min="18" max="100"
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('idade') border-red-300 @enderror" required>
                                    @error('idade')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Dados Profissionais -->
                            <div class="space-y-4">
                                <h3 class="text-lg font-semibold text-gray-900">Dados Profissionais</h3>
                                
                                <div>
                                    <label for="crm" class="block text-sm font-medium text-gray-700">CRM</label>
                                    <input type="text" name="crm" id="crm" value="{{ old('crm') }}" 
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('crm') border-red-300 @enderror" required>
                                    @error('crm')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="especialidade" class="block text-sm font-medium text-gray-700">Especialidade</label>
                                    <input type="text" name="especialidade" id="especialidade" value="{{ old('especialidade') }}" 
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('especialidade') border-red-300 @enderror" required>
                                    @error('especialidade')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="telefone" class="block text-sm font-medium text-gray-700">Telefone</label>
                                    <input type="text" name="telefone" id="telefone" value="{{ old('telefone') }}" 
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('telefone') border-red-300 @enderror">
                                    @error('telefone')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="endereco" class="block text-sm font-medium text-gray-700">Endereço</label>
                                    <textarea name="endereco" id="endereco" rows="3" 
                                              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('endereco') border-red-300 @enderror">{{ old('endereco') }}</textarea>
                                    @error('endereco')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end space-x-4 mt-8">
                            <a href="{{ route('admin.medicos.index') }}" 
                               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Cancelar
                            </a>
                            <button type="submit" 
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Cadastrar Médico
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
