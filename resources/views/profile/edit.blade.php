<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black text-2xl text-gray-800 leading-tight flex items-center gap-2">
            <span class="bg-indigo-100 text-indigo-600 p-2 rounded-lg">
                <i class="fas fa-user-shield"></i>
            </span>
            Mi Cuenta
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start">
                <div class="px-4 sm:px-0">
                    <h3 class="text-lg font-black text-gray-800 uppercase tracking-wider text-xs italic text-indigo-500 mb-1">Paso 01</h3>
                    <h3 class="text-xl font-bold text-gray-900">Información Personal</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        Actualiza tu nombre de usuario y la dirección de correo electrónico vinculada a tu acceso.
                    </p>
                </div>

                <div class="md:col-span-2 p-6 sm:p-10 bg-white shadow-sm border border-gray-100 rounded-3xl relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-indigo-500 to-blue-500"></div>
                    <div class="max-w-xl">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>
            </div>

            <hr class="border-gray-200">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start">
                <div class="px-4 sm:px-0">
                    <h3 class="text-lg font-black text-gray-800 uppercase tracking-wider text-xs italic text-indigo-500 mb-1">Paso 02</h3>
                    <h3 class="text-xl font-bold text-gray-900">Seguridad</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        Asegúrate de que tu cuenta esté usando una contraseña larga y aleatoria para mantener la seguridad.
                    </p>
                </div>

                <div class="md:col-span-2 p-6 sm:p-10 bg-white shadow-sm border border-gray-100 rounded-3xl relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-purple-500 to-indigo-500"></div>
                    <div class="max-w-xl">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>
            </div>

            <hr class="border-gray-200">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start">
                <div class="px-4 sm:px-0">
                    <h3 class="text-lg font-black text-red-500 uppercase tracking-wider text-xs italic mb-1">Peligro</h3>
                    <h3 class="text-xl font-bold text-gray-900">Eliminar Acceso</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        Una vez que se elimine tu cuenta, todos sus recursos y datos se borrarán permanentemente.
                    </p>
                </div>

                <div class="md:col-span-2 p-6 sm:p-10 bg-red-50/50 border border-red-100 rounded-3xl relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-1 bg-red-500"></div>
                    <div class="max-w-xl">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>