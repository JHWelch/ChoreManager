@props([
  'class' => ''
])

<div
  x-data="{ show: false }"
  class="relative inline-block text-left {{ $class }}"
>
  <button x-on:click="show = !show">
    <x-icons.dots-vertical class="w-6 h-6 text-gray-500 dark:text-gray-400" />
  </button>

  <div
    x-cloak
    x-show="show"
    @click.away="show = false"
    class="absolute z-30 mt-2 bg-white border border-gray-200 rounded-md shadow-md right-1 ring-1 ring-black ring-opacity-5 -top-2 dark:bg-gray-800 dark:border-gray-600"
  >
    <div
      class="py-1 divide-y divide-gray-200 dark:divide-gray-600"
      role="menu"
      aria-orientation="vertical"
      aria-labelledby="options-menu"
    >
      {{ $slot }}
    </div>
  </div>
</div>
