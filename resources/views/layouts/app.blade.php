<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', 'Dashboard') - {{ config('app.name', 'Laundry Kami') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/css/staff.css', 'resources/js/app.js', 'resources/js/staff.js'])
    </head>
    <body class="min-h-screen bg-slate-950 font-sans text-slate-100 antialiased {{ request()->routeIs('dashboard') ? 'dashboard-screen' : 'workspace-screen' }}">
        <div class="staff-app-shell min-h-screen">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="app-page-header">
                    <div class="container-fluid app-container">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="staff-main">
                {{ $slot ?? '' }}
                @yield('content')
            </main>
        </div>
    </body>
</html>
