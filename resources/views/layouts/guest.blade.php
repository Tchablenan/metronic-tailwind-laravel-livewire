<!DOCTYPE html>
<html class="h-full" data-kt-theme="true" data-kt-theme-mode="light" dir="ltr" lang="en">
    <head>
        @include('layouts.partials.head')
        @livewireStyles
    </head>
    <body class="antialiased flex h-full text-base text-foreground bg-background">


        <livewire:shared.theme-toggle />

        <!-- Page -->
        <!-- Main -->



            <!-- Wrapper -->
            <div class="kt-wrapper flex grow flex-col">


                <!-- Content -->
                <main class="grow pt-5" id="content" role="content">
                    {{ $slot }}
                </main>
                <!-- End of Content -->


            </div>
            <!-- End of Wrapper -->
        </div>
        <!-- End of Main -->
        <!-- End of Page -->

        @include('layouts.partials.scripts')
        @livewireScripts
    </body>
</html>
