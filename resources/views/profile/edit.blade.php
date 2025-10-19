<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800">
            {{ __('Editar Perfil') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-6 sm:p-8 bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-6 sm:p-8 bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-6 sm:p-8 bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>

    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }

        .bg-white {
            background-color: #ffffff;
        }

        .text-gray-800 {
            color: #1f2937;
        }

        .rounded-lg {
            border-radius: 0.5rem;
        }

        .shadow-md {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .hover\:shadow-lg:hover {
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.15);
        }

        .transition-shadow {
            transition-property: box-shadow;
        }

        .duration-300 {
            transition-duration: 300ms;
        }
    </style>
</x-app-layout>
