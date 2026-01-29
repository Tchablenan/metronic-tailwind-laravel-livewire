<!DOCTYPE html>
<html class="h-full" data-kt-theme="true" dir="ltr" lang="fr">
<head>
    @include('layouts.partials.head')
    @livewireStyles
</head>

<body class="flex h-full text-base text-foreground bg-background antialiased">
    <livewire:shared.theme-toggle />

    <div class="kt-wrapper flex grow flex-col w-full">
        <main class="grow flex items-center justify-center" role="main">
            {{ $slot }}
        </main>
    </div>

    @include('layouts.partials.scripts')
    @livewireScripts
</body>
</html>
