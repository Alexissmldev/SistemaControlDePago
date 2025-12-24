<nav
    x-data="{ open: false }"
    class="sticky top-0 z-40 border-b border-gray-100 bg-white/95 shadow-sm backdrop-blur-sm"
>
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 justify-between">
            <div class="flex">
                {{-- Logo --}}
                <div class="flex shrink-0 items-center">
                    <a href="{{ route('dashboard') }}" class="transition hover:opacity-80">
                        <x-application-logo class="block h-8 w-auto fill-current text-indigo-600" />
                    </a>
                </div>

                {{-- Links Principales (Desktop) --}}
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        <i class="fas fa-chart-pie mr-2 text-xs"></i>
                        {{ __('Panel') }}
                    </x-nav-link>

                    <x-nav-link :href="route('students.index')" :active="request()->routeIs('students.*')">
                        <i class="fas fa-user-graduate mr-2 text-xs"></i>
                        {{ __('Estudiantes') }}
                    </x-nav-link>

                    <x-nav-link :href="route('courses.index')" :active="request()->routeIs('courses.*')">
                        <i class="fas fa-book-open mr-2 text-xs"></i>
                        {{ __('Cursos') }}
                    </x-nav-link>

                    <x-nav-link :href="route('settings.index')" :active="request()->routeIs('settings.index')">
                        <i class="fas fa-sliders-h mr-2 text-xs"></i>
                        {{ __('Ajustes') }}
                    </x-nav-link>
                </div>
            </div>

            {{-- Menú de Usuario (Desktop) --}}
            <div class="hidden sm:ms-6 sm:flex sm:items-center">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center gap-3 rounded-xl border border-gray-100 bg-gray-50 px-3 py-1.5 text-sm font-bold text-gray-600 shadow-sm transition hover:bg-gray-100"
                        >
                            <div
                                class="flex h-7 w-7 items-center justify-center rounded-lg bg-indigo-600 text-[10px] font-black text-white shadow-inner"
                            >
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>

                            <span class="max-w-[120px] truncate">{{ Auth::user()->name }}</span>

                            <i class="fas fa-chevron-down text-[10px] opacity-40"></i>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')" class="flex items-center gap-2">
                            <i class="fas fa-user-circle text-gray-400"></i>
                            {{ __('Mi Perfil') }}
                        </x-dropdown-link>

                        <hr class="border-gray-100" />

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link
                                :href="route('logout')"
                                onclick="
                                    event.preventDefault();
                                    this.closest('form').submit();
                                "
                                class="flex items-center gap-2 font-bold text-red-600 hover:bg-red-50"
                            >
                                <i class="fas fa-sign-out-alt"></i>
                                {{ __('Cerrar Sesión') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            {{-- Botón Móvil --}}
            <div class="-me-2 flex items-center sm:hidden">
                <button
                    @click="open = ! open"
                    class="inline-flex items-center justify-center rounded-lg p-2 text-gray-400 transition hover:bg-gray-100"
                >
                    <i class="fas" :class="{'fa-times': open, 'fa-bars': !open }"></i>
                </button>
            </div>
        </div>
    </div>

    {{-- Menú Móvil --}}
    <div x-show="open" x-cloak class="border-t border-gray-100 bg-white shadow-xl sm:hidden">
        <div class="space-y-1 pb-3 pt-2">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                <i class="fas fa-chart-pie mr-3 w-5"></i>
                {{ __('Panel Control') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('students.index')" :active="request()->routeIs('students.*')">
                <i class="fas fa-user-graduate mr-3 w-5"></i>
                {{ __('Estudiantes') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('courses.index')" :active="request()->routeIs('courses.*')">
                <i class="fas fa-book-open mr-3 w-5"></i>
                {{ __('Cursos') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('settings.index')" :active="request()->routeIs('settings.index')">
                <i class="fas fa-sliders-h mr-3 w-5"></i>
                {{ __('Ajustes del Sistema') }}
            </x-responsive-nav-link>
        </div>

        <div class="border-t border-gray-200 bg-gray-50/50 pb-1 pt-4">
            <div class="flex items-center gap-3 px-4">
                <div
                    class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-600 text-lg font-black text-white shadow-md"
                >
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div>
                    <div class="text-sm font-bold text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="text-[10px] font-medium uppercase tracking-tighter text-gray-500">
                        {{ Auth::user()->email }}
                    </div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')" class="font-bold">
                    <i class="fas fa-id-badge mr-3"></i>
                    {{ __('Mi Perfil') }}
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link
                        :href="route('logout')"
                        onclick="
                            event.preventDefault();
                            this.closest('form').submit();
                        "
                        class="border-l-red-500 font-bold text-red-600"
                    >
                        <i class="fas fa-power-off mr-3"></i>
                        {{ __('Salir del Sistema') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
