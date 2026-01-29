<!-- Header -->
<header class="fixed top-0 left-0 right-0 z-40 bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700 h-16 shadow-sm" id="header">
    <div class="px-4 sm:px-6 lg:px-8 h-full flex items-center justify-between gap-4">

        <!-- Mobile Logo -->
        <div class="flex items-center gap-3 lg:gap-6">
            <a href="{{ route('dashboard') }}" class="flex items-center hover:opacity-90 transition-opacity">
                <img class="h-12 w-auto object-contain" src="{{ asset('images/cmo-vistamd-logo.jpg') }}" alt="CMO VistaMD" />
            </a>
            <button class="inline-flex items-center justify-center lg:hidden w-10 h-10 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors"
                    data-kt-drawer-toggle="#sidebar">
                <i class="ki-filled ki-menu text-xl text-gray-600 dark:text-gray-400"></i>
            </button>
        </div>
        <!-- End of Mobile Logo -->

        <!-- Mega Menu / Navigation -->
        <div class="flex items-stretch" id="mega_menu_container">
            <!-- Vide pour l'instant -->
        </div>

        <!-- Topbar -->
        <div class="flex items-center gap-2.5 ml-auto">

            {{-- Recherche --}}
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open"
                        class="inline-flex items-center justify-center w-9 h-9 rounded-full hover:bg-blue-50 hover:text-blue-600 transition-colors">
                    <i class="ki-filled ki-magnifier text-xl text-gray-600"></i>
                </button>

                <!-- Dropdown -->
                <div x-show="open"
                     @click.away="open = false"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="absolute right-0 mt-2 w-[550px] bg-white rounded-lg shadow-xl border border-gray-200 overflow-hidden z-50"
                     style="display: none;">
                    <div class="p-5">
                        <div class="relative">
                            <i class="ki-filled ki-magnifier absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            <input type="text"
                                   placeholder="Rechercher des patients, rendez-vous..."
                                   class="w-full pl-10 pr-20 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
                            <span class="absolute right-3 top-1/2 -translate-y-1/2 px-2 py-1 text-xs bg-gray-100 text-gray-600 rounded">
                                <kbd>Ctrl + K</kbd>
                            </span>
                        </div>
                    </div>
                    <div class="border-t border-gray-200"></div>
                    <div class="p-5">
                        <div class="text-xs text-gray-500 font-medium pb-2.5">Raccourcis</div>
                        <div class="space-y-1">
                            @role('doctor')
                            <a href="{{ route('users.index') }}"
                               class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                                <i class="ki-filled ki-people text-gray-500"></i>
                                Gestion des utilisateurs
                            </a>
                            @endrole
                        </div>
                    </div>
                </div>
            </div>

            {{-- Notifications --}}
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open"
                        class="relative inline-flex items-center justify-center w-9 h-9 rounded-full hover:bg-blue-50 hover:text-blue-600 transition-colors">
                    <i class="ki-filled ki-notification-on text-xl text-gray-600"></i>
                    <!-- Badge -->
                    <span class="absolute top-0.5 right-0.5 w-2 h-2 bg-green-500 rounded-full border-2 border-white"></span>
                </button>

                <!-- Dropdown -->
                <div x-show="open"
                     @click.away="open = false"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="absolute right-0 mt-2 w-[460px] bg-white rounded-lg shadow-xl border border-gray-200 overflow-hidden z-50"
                     style="display: none;">
                    <div class="flex items-center justify-between px-5 py-3 border-b border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-900">Notifications</h3>
                        <span class="px-2 py-0.5 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">3 nouvelles</span>
                    </div>
                    <div class="max-h-96 overflow-y-auto">
                        <!-- Notification 1 -->
                        <a href="#" class="flex items-start gap-3 px-5 py-3 hover:bg-gray-50 transition-colors border-b border-gray-100">
                            <div class="flex items-center justify-center w-10 h-10 rounded-full bg-blue-100 shrink-0">
                                <i class="ki-filled ki-calendar text-lg text-blue-600"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900">Nouveau rendez-vous</p>
                                <p class="text-xs text-gray-600 mt-1">Patient: Jean Dupont - 14h00</p>
                                <p class="text-xs text-gray-500 mt-1">Il y a 5 minutes</p>
                            </div>
                        </a>
                        <!-- Notification 2 -->
                        <a href="#" class="flex items-start gap-3 px-5 py-3 hover:bg-gray-50 transition-colors border-b border-gray-100">
                            <div class="flex items-center justify-center w-10 h-10 rounded-full bg-yellow-100 shrink-0">
                                <i class="ki-filled ki-question text-lg text-yellow-600"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900">Demande de service</p>
                                <p class="text-xs text-gray-600 mt-1">Nouvelle demande de transport médical</p>
                                <p class="text-xs text-gray-500 mt-1">Il y a 1 heure</p>
                            </div>
                        </a>
                    </div>
                    <div class="px-4 py-3 border-t border-gray-200">
                        <a href="#" class="block w-full px-4 py-2 text-center text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                            Voir toutes les notifications
                        </a>
                    </div>
                </div>
            </div>

            <!-- User Menu Button -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" type="button" class="inline-flex items-center justify-center gap-2 px-3 h-10 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                    <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-white text-sm font-bold">
                        {{ strtoupper(substr(auth()->user()->first_name ?? auth()->user()->name, 0, 1)) }}{{ strtoupper(substr(auth()->user()->last_name ?? 'U', 0, 1)) }}
                    </div>
                    <i class="ki-filled ki-down text-xs text-gray-600 dark:text-gray-400 hidden sm:block"></i>
                </button>

                <!-- User Dropdown -->
                <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-56 bg-white dark:bg-gray-800 rounded-lg shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden z-50">

                    <!-- User Info -->
                    <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-white text-sm font-bold">
                                {{ strtoupper(substr(auth()->user()->first_name ?? auth()->user()->name, 0, 1)) }}{{ strtoupper(substr(auth()->user()->last_name ?? 'U', 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ auth()->user()->first_name ?? auth()->user()->name }} {{ auth()->user()->last_name ?? '' }}</p>

                    <!-- Menu Items -->
                    <div class="py-2">
                        <a href="#" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                            <i class="ki-filled ki-profile-circle text-gray-500 dark:text-gray-400"></i>
                            <span>Mon Profil</span>
                        </a>
                        <a href="#" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                            <i class="ki-filled ki-setting-2 text-gray-500 dark:text-gray-400"></i>
                            <span>Paramètres</span>
                        </a>
                        @role('doctor')
                        <a href="{{ route('users.index') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                            <i class="ki-filled ki-security-user text-gray-500 dark:text-gray-400"></i>
                            <span>Administration</span>
                        </a>
                        @endrole
                    </div>

                    <!-- Logout -->
                    <div class="border-t border-gray-200 dark:border-gray-700 px-4 py-2">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="flex items-center justify-center gap-2 w-full px-3 py-2 text-sm font-medium text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors">
                                <i class="ki-filled ki-entrance-left"></i>
                                <span>Déconnexion</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</header>
<!-- End of Header -->

