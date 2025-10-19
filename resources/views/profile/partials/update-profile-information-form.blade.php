<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            Informações do Perfil
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            Atualize as informações do seu perfil e endereço de email.
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <x-input-label for="name" value="Nome Completo" />
                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            <div>
                <x-input-label for="email" value="Email" />
                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
                <x-input-error class="mt-2" :messages="$errors->get('email')" />

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div>
                        <p class="text-sm mt-2 text-gray-800">
                            Seu endereço de email não foi verificado.

                            <button form="send-verification" class="underline text-sm text-gray-600">
                                Clique aqui para reenviar o email de verificação.
                            </button>
                        </p>

                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-2 font-medium text-sm text-green-600">
                                Um novo link de verificação foi enviado para seu endereço de email.
                            </p>
                        @endif
                    </div>
                @endif
            </div>

            <div>
                <x-input-label for="cpf" value="CPF" />
                <x-text-input id="cpf" name="cpf" type="text" class="mt-1 block w-full" :value="old('cpf', $user->cpf)" required autocomplete="cpf" />
                <x-input-error class="mt-2" :messages="$errors->get('cpf')" />
            </div>

            <div>
                <x-input-label for="sexo" value="Sexo" />
                <select name="sexo" id="sexo" class="mt-1 block w-full border-gray-300" required>
                    <option value="">Selecione</option>
                    <option value="M" {{ old('sexo', $user->sexo) == 'M' ? 'selected' : '' }}>Masculino</option>
                    <option value="F" {{ old('sexo', $user->sexo) == 'F' ? 'selected' : '' }}>Feminino</option>
                    <option value="Outro" {{ old('sexo', $user->sexo) == 'Outro' ? 'selected' : '' }}>Outro</option>
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('sexo')" />
            </div>

            <div>
                <x-input-label for="idade" value="Idade" />
                <x-text-input id="idade" name="idade" type="number" min="1" max="150" class="mt-1 block w-full" :value="old('idade', $user->idade)" required autocomplete="idade" />
                <x-input-error class="mt-2" :messages="$errors->get('idade')" />
            </div>
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>Salvar</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                     class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2.5 px-4 rounded-md text-sm sm:text-base transition-colors"
                >Salvo.</p>
            @endif
        </div>
    </form>
</section>
