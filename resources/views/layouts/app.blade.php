<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net" />
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            [x-cloak] {
                display: none !important;
            }
        </style>
    </head>

    <body class="bg-gray-100 font-sans antialiased">
        <div class="min-h-screen">
            @include('layouts.navigation')

            @isset($header)
                <header class="bg-white shadow-sm">
                    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <main>
                @isset($slot)
                    {{ $slot }}
                @endisset

                @yield('content')
            </main>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const toastConfig = {
                    confirmButtonColor: '#4f46e5',
                    timer: 3000
                };

                @if (session('success'))
                    Swal.fire({
                        ...toastConfig,
                        icon: 'success',
                        title: 'Operación Exitosa',
                        text: "{{ session('success') }}"
                    });
                @endif

                @if (session('error'))
                    Swal.fire({
                        ...toastConfig,
                        icon: 'error',
                        title: 'Hubo un error',
                        text: "{{ session('error') }}",
                        confirmButtonColor: '#ef4444'
                    });
                @endif
            });

            window.addEventListener("pageshow", function (event) {
                if (event.persisted) {
                    const alerts = document.querySelectorAll('.swal2-container');
                    alerts.forEach(alert => alert.remove());
                }
            });
        </script>

        @stack('scripts')
    </body>
</html>
