<!-- Sidebar -->
<div class="kt-sidebar fixed bottom-0 top-0 z-20 hidden shrink-0 flex-col items-stretch border-e border-e-border bg-background [--kt-drawer-enable:true] lg:flex lg:[--kt-drawer-enable:false]"
    data-kt-drawer="true" data-kt-drawer-class="kt-drawer kt-drawer-start top-0 bottom-0" id="sidebar">
    <div class="kt-sidebar-header relative hidden shrink-0 items-center justify-between px-3 lg:flex lg:px-6 h-16 border-b border-b-border"
        id="sidebar_header">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
            <img class="h-12 w-auto object-contain" src="{{ asset('images/cmo-vistamd-logo.jpg') }}" alt="CMO VISTAMD" />
        </a>
        <button
            class="kt-btn kt-btn-outline kt-btn-icon absolute start-full top-2/4 size-[30px] -translate-x-2/4 -translate-y-2/4 rtl:translate-x-2/4 hidden lg:inline-flex"
            data-kt-toggle="body" data-kt-toggle-class="kt-sidebar-collapse" id="sidebar_toggle">
            <i class="ki-filled ki-black-left-line kt-toggle-active:rotate-180 rtl:translate rtl:kt-toggle-active:rotate-0 transition-all duration-300 rtl:rotate-180"></i>
        </button>
    </div>

    <div class="kt-sidebar-content flex shrink-0 grow py-5 pe-2 overflow-y-auto" id="sidebar_content">
        <div class="kt-scrollable-y-hover flex shrink-0 grow pe-1 ps-2 lg:pe-3 lg:ps-5 w-full" data-kt-scrollable="true"
            data-kt-scrollable-dependencies="#sidebar_header" data-kt-scrollable-height="auto"
            data-kt-scrollable-offset="0px" data-kt-scrollable-wrappers="#sidebar_content" id="sidebar_scrollable">
            <!-- Sidebar Menu -->
            <div class="kt-menu flex grow flex-col gap-1 w-full" data-kt-menu="true" data-kt-menu-accordion-expand-all="false" id="sidebar_menu">

                <!-- Dashboard -->
                <a href="{{ route('dashboard') }}" class="kt-menu-item">
                    <div class="kt-menu-link flex grow items-center gap-[10px] border border-transparent py-[6px] pe-[10px] ps-[10px] rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors {{ request()->routeIs('dashboard') ? 'bg-primary/10 text-primary' : '' }}">
                        <span class="kt-menu-icon w-[20px] text-base">
                            <i class="ki-filled ki-element-11"></i>
                        </span>
                        <span class="kt-menu-title text-sm font-medium text-foreground">
                            Dashboard
                        </span>
                    </div>
                </a>

                <!-- Separator -->
                <div class="my-2 border-t border-t-border"></div>

                <!-- User Management (Doctor only) -->
                @role('doctor')
                <a href="{{ route('users.index') }}" class="kt-menu-item">
                    <div class="kt-menu-link flex grow items-center gap-[10px] border border-transparent py-[6px] pe-[10px] ps-[10px] rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors {{ request()->routeIs('users.*') ? 'bg-primary/10 text-primary' : '' }}">
                        <span class="kt-menu-icon w-[20px] text-base">
                            <i class="ki-filled ki-people"></i>
                        </span>
                        <span class="kt-menu-title text-sm font-medium text-foreground">
                            Utilisateurs
                        </span>
                    </div>
                </a>
                @endrole

                <!-- Appointments -->
                @can('viewAny', App\Models\Appointment::class)
                <a href="{{ route('appointments.index') }}" class="kt-menu-item">
                    <div class="kt-menu-link flex grow items-center gap-[10px] border border-transparent py-[6px] pe-[10px] ps-[10px] rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors {{ request()->routeIs('appointments.*') ? 'bg-primary/10 text-primary' : '' }}">
                        <span class="kt-menu-icon w-[20px] text-base">
                            <i class="ki-filled ki-calendar"></i>
                        </span>
                        <span class="kt-menu-title text-sm font-medium text-foreground">
                            Rendez-vous
                        </span>
                    </div>
                </a>
                @endcan

                <!-- Service Requests (Secretary & Doctor/Chief) -->
                @can('viewAny', App\Models\ServiceRequest::class)
                <a href="{{ route('service-requests.index') }}" class="kt-menu-item">
                    <div class="kt-menu-link flex grow items-center gap-[10px] border border-transparent py-[6px] pe-[10px] ps-[10px] rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors {{ request()->routeIs('service-requests.*') ? 'bg-primary/10 text-primary' : '' }}">
                        <span class="kt-menu-icon w-[20px] text-base">
                            <i class="ki-filled ki-clipboard-list"></i>
                        </span>
                        <span class="kt-menu-title text-sm font-medium text-foreground">
                            Demandes de service
                        </span>
                    </div>
                </a>
                @endcan

                <!-- Secretary Service Requests Management (Secretary only) -->
                @role('secretary')
                <a href="{{ route('secretary.service-requests.index') }}" class="kt-menu-item">
                    <div class="kt-menu-link flex grow items-center gap-[10px] border border-transparent py-[6px] pe-[10px] ps-[10px] rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors {{ request()->routeIs('secretary.service-requests.*') ? 'bg-primary/10 text-primary' : '' }}">
                        <span class="kt-menu-icon w-[20px] text-base">
                            <i class="ki-filled ki-plus-circle"></i>
                        </span>
                        <span class="kt-menu-title text-sm font-medium text-foreground">
                            Créer une demande
                        </span>
                    </div>
                </a>
                @endrole

                <!-- Separator -->
                <div class="my-2 border-t border-t-border"></div>

                <!-- Account Section -->
                <div class="text-xs font-semibold text-muted-foreground px-3 py-2">MON COMPTE</div>

                <a href="#" class="kt-menu-item">
                    <div class="kt-menu-link flex grow items-center gap-[10px] border border-transparent py-[6px] pe-[10px] ps-[10px] rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                        <span class="kt-menu-icon w-[20px] text-base">
                            <i class="ki-filled ki-profile-circle"></i>
                        </span>
                        <span class="kt-menu-title text-sm font-medium text-foreground">
                            Profil
                        </span>
                    </div>
                </a>

                <a href="#" class="kt-menu-item">
                    <div class="kt-menu-link flex grow items-center gap-[10px] border border-transparent py-[6px] pe-[10px] ps-[10px] rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                        <span class="kt-menu-icon w-[20px] text-base">
                            <i class="ki-filled ki-setting-2"></i>
                        </span>
                        <span class="kt-menu-title text-sm font-medium text-foreground">
                            Paramètres
                        </span>
                    </div>
                </a>

                <form action="{{ route('logout') }}" method="POST" class="kt-menu-item">
                    @csrf
                    <button type="submit" class="w-full kt-menu-link flex grow items-center gap-[10px] border border-transparent py-[6px] pe-[10px] ps-[10px] rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors hover:text-danger">
                        <span class="kt-menu-icon w-[20px] text-base">
                            <i class="ki-filled ki-exit-right"></i>
                        </span>
                        <span class="kt-menu-title text-sm font-medium text-foreground">
                            Déconnexion
                        </span>
                    </button>
                </form>

            </div>
            <!-- End of Sidebar Menu -->
        </div>
    </div>
</div>
<!-- End of Sidebar -->
