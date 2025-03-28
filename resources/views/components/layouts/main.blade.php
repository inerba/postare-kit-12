@props([
    'title' => '',
])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <title>{{ $title }}</title>
        @livewireStyles

        <!-- Scripts and Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Additional Styles -->
        @stack('styles')
    </head>
    <body class="flex min-h-screen flex-col font-sans antialiased">
        <x-partials.header />
        <main class="flex-1 bg-rose-50">
            {!! $slot !!}
        </main>
        <x-partials.footer />
        @livewireScripts
        @stack('scripts')
    </body>
</html>
