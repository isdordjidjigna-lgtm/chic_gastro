@php
    $user = Auth::user();
    $liensAdmin = [
        ['admin.plats.index', 'Plats'],
        ['admin.categories.index', 'Catégories'],
        ['admin.tables.index', 'Tables & QR'],
        ['admin.users.index', 'Utilisateurs'],
        ['admin.commandes.index', 'Commandes'],
        ['admin.paiements.index', 'Paiements'],
        ['admin.stats', 'Statistiques'],
    ];
@endphp

<nav x-data="{ open: false, adminOpen: false }" class="bg-white border-b border-amber-100 shadow-sm">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="text-lg" />
                    </a>
                </div>

                <!-- Navigation Links (par rôle) -->
                <div class="hidden space-x-6 sm:-my-px sm:ms-8 sm:flex sm:items-center">
                    @if($user->isAdmin())
                        <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                            {{ __('Tableau de bord') }}
                        </x-nav-link>
                        <div class="relative" @click.away="adminOpen = false">
                            <button @click="adminOpen = !adminOpen"
                                    class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition duration-150 ease-in-out focus:outline-none {{ request()->routeIs('admin.*') && !request()->routeIs('admin.dashboard') ? 'border-amber-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                                {{ __('Administration') }}
                                <svg class="ms-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="adminOpen" x-cloak class="absolute left-0 mt-2 w-52 bg-white rounded-xl shadow-lg border border-gray-100 py-2 z-50">
                                @foreach($liensAdmin as [$route, $label])
                                    <a href="{{ route($route) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-amber-50 hover:text-amber-700 {{ request()->routeIs($route.'*') ? 'font-semibold text-amber-700' : '' }}">
                                        {{ __($label) }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @elseif($user->isCuisinier())
                        <x-nav-link :href="route('cuisine.dashboard')" :active="request()->routeIs('cuisine.*')">
                            {{ __('Cuisine') }}
                        </x-nav-link>
                    @else
                        <x-nav-link :href="route('serveur.dashboard')" :active="request()->routeIs('serveur.*')">
                            {{ __('Service') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="56">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-lg text-gray-600 hover:text-gray-900 hover:bg-amber-50 focus:outline-none transition ease-in-out duration-150">
                            <div class="text-right">
                                <div>{{ Auth::user()->name }}</div>
                                <span class="inline-block mt-0.5 px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wide {{ $user->isAdmin() ? 'bg-amber-100 text-amber-800' : ($user->isCuisinier() ? 'bg-orange-100 text-orange-800' : 'bg-blue-100 text-blue-800') }}">
                                    {{ $user->roleLabel() }}
                                </span>
                            </div>
                            <div class="ms-2">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-600 hover:bg-amber-50 focus:outline-none focus:bg-amber-50 focus:text-amber-600 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden border-t border-amber-100">
        <div class="pt-2 pb-3 space-y-1">
            @if($user->isAdmin())
                <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                    {{ __('Tableau de bord') }}
                </x-responsive-nav-link>
                <p class="px-4 pt-2 text-xs font-bold uppercase tracking-wide text-gray-400">{{ __('Administration') }}</p>
                @foreach($liensAdmin as [$route, $label])
                    <x-responsive-nav-link :href="route($route)" :active="request()->routeIs($route.'*')">
                        {{ __($label) }}
                    </x-responsive-nav-link>
                @endforeach
            @elseif($user->isCuisinier())
                <x-responsive-nav-link :href="route('cuisine.dashboard')" :active="request()->routeIs('cuisine.*')">
                    {{ __('Cuisine') }}
                </x-responsive-nav-link>
            @else
                <x-responsive-nav-link :href="route('serveur.dashboard')" :active="request()->routeIs('serveur.*')">
                    {{ __('Service') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-2 border-t border-gray-200">
            <div class="px-4 flex items-center justify-between">
                <div>
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>
                <span class="px-2 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide {{ $user->isAdmin() ? 'bg-amber-100 text-amber-800' : ($user->isCuisinier() ? 'bg-orange-100 text-orange-800' : 'bg-blue-100 text-blue-800') }}">
                    {{ $user->roleLabel() }}
                </span>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
