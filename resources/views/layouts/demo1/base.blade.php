<!DOCTYPE html>
<html class="h-full" data-kt-theme="true" data-kt-theme-mode="light" dir="ltr" lang="en">

<head>
    @include('layouts.partials.head')
    @livewireStyles
</head>

<body class="demo1 kt-sidebar-fixed kt-header-fixed flex h-full bg-background text-base text-foreground antialiased">
    <livewire:shared.theme-toggle />

    <div class="flex grow">
        <livewire:demo1.sidebar />

        <div class="kt-wrapper flex grow flex-col">
            @persist('header')
                <livewire:demo1.header />
            @endpersist

            <main class="grow pt-5" id="content" role="main">
                @yield('content')
            </main>

            <livewire:demo1.footer />
        </div>
    </div>

    @include('layouts.partials.scripts')
    @livewireScripts
</body>

</html>
</html>
