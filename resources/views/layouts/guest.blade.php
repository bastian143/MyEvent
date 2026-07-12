<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased bg-slate-50 relative min-h-screen">
        <!-- Background Decor -->
        <div class="fixed top-0 left-0 w-[800px] h-[800px] bg-brand-500/10 blur-[120px] rounded-full pointer-events-none z-0 -translate-x-1/2 -translate-y-1/2"></div>
        <div class="fixed bottom-0 right-0 w-[600px] h-[600px] bg-amber-500/10 blur-[100px] rounded-full pointer-events-none z-0 translate-x-1/3 translate-y-1/3"></div>

        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 relative z-10">
            <div>
                <a href="/">
                    <x-application-logo class="w-20 h-20 fill-current text-brand-500 drop-shadow-md" />
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-8 bg-white/70 backdrop-blur-xl border border-white/50 shadow-xl overflow-hidden sm:rounded-2xl">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
