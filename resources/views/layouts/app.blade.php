<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="icon" href="https://fav.farm/🧹" />

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

    <!-- Styles -->
    @vite('resources/css/app.css')

    @livewireStyles

    <!-- Scripts -->
    @vite('resources/js/app.js')

    <!-- Page specific head -->
    @stack('head')
  </head>
  <body class="font-sans antialiased bg-gray-100">
    @livewire('demo-banner')

    <x-jet-banner />

    <div class="h-full min-h-screen">
      @livewire('navigation-menu')

      <!-- Page Heading -->
      @if (isset($header))
        <header class="bg-white shadow">
          <div class="px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
            {{ $header }}
          </div>
        </header>
      @endif

      <!-- Page Content -->
      <main class="max-h-full py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
        {{ $slot }}
      </main>
    </div>

    @stack('modals')

    @livewireScripts
  </body>
</html>
