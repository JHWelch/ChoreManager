<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="icon" href="https://fav.farm/🧹" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Styles -->
    @livewireStyles

    <!-- Page specific head -->
    @stack('head')
  </head>

  <body class="font-sans antialiased bg-gray-100 dark:bg-gray-900">
    @livewire('demo-banner')

    <x-banner />

    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
      @livewire('navigation-menu')

      <!-- Page Heading -->
      @if (isset($header))
        <header class="bg-white shadow dark:bg-gray-800">
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
    @livewire('wire-elements-modal')
    @livewireScriptConfig
  </body>
</html>
