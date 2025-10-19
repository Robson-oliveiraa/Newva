<nav x-data="{ open: false }" class="border-b border-gray-200">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <div class="flex items-center">
                <!-- Navigation Links -->
                <div class="hidden space-x-6 sm:-my-px sm:ms-8 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-gray-700 hover:text-blue-700 text-sm sm:text-base font-semibold transition-colors">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    <x-nav-link :href="route('consultas.index')" :active="request()->routeIs('consultas.*')" class="text-gray-700 hover:text-blue-700 text-sm sm:text-base font-semibold transition-colors">
                        {{ __('Consultas') }}
                    </x-nav-link>
                    <x-nav-link :href="route('carteira-vacina.index')" :active="request()->routeIs('carteira-vacina.*')" class="text-gray-700 hover:text-blue-700 text-sm sm:text-base font-semibold transition-colors">
                        {{ __('Carteira de Vacina') }}
                    </x-nav-link>
                    @if(auth()->user()->hasRole('administrator'))
                        <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.*')" class="text-gray-700 hover:text-blue-700 text-sm sm:text-base font-semibold transition-colors">
                            {{ __('Administração') }}
                        </x-nav-link>
                    @endif
                    <x-nav-link :href="route('postos-saude.index')" :active="request()->routeIs('postos-saude.*')" class="text-gray-700 hover:text-blue-700 text-sm sm:text-base font-semibold transition-colors">
                        {{ __('Postos de Saúde') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm sm:text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 transition ease-in-out duration-150" aria-haspopup="true" aria-expanded="false">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ms-2">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.show')" class="text-sm sm:text-base">
                            {{ __('Ver Perfil') }}
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('profile.edit')" class="text-sm sm:text-base">
                            {{ __('Editar Perfil') }}
                        </x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="text-sm sm:text-base">
                                {{ __('Sair') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-3 rounded-md text-gray-600 hover:text-blue-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-blue-700 transition duration-150 ease-in-out" aria-label="Abrir/fechar menu" aria-expanded="false" x-bind:aria-expanded="open">
                    <svg class="h-7 w-7" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden bg-white border-b border-gray-200">
        <div class="pt-2 pb-3 space-y-2 px-4">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-gray-700 hover:text-blue-700 text-base font-semibold">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('consultas.index')" :active="request()->routeIs('consultas.*')" class="text-gray-700 hover:text-blue-700 text-base font-semibold">
                {{ __('Consultas') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('carteira-vacina.index')" :active="request()->routeIs('carteira-vacina.*')" class="text-gray-700 hover:text-blue-700 text-base font-semibold">
                {{ __('Carteira de Vacina') }}
            </x-responsive-nav-link>
            @if(auth()->user()->hasRole('administrator'))
                <x-responsive-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.*')" class="text-gray-700 hover:text-blue-700 text-base font-semibold">
                    {{ __('Administração') }}
                </x-responsive-nav-link>
            @endif
            <x-responsive-nav-link :href="route('postos-saude.index')" :active="request()->routeIs('postos-saude.*')" class="text-gray-700 hover:text-blue-700 text-base font-semibold">
                {{ __('Postos de Saúde') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-3 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>
            <div class="mt-3 space-y-2 px-4">
                <x-responsive-nav-link :href="route('profile.show')" class="text-base">
                    {{ __('Ver Perfil') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('profile.edit')" class="text-base">
                    {{ __('Editar Perfil') }}
                </x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="text-base">
                        {{ __('Sair') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>