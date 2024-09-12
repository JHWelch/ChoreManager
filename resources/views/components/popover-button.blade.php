<button
  wire:click.prevent="{{ $click }}"
  class="flex flex-col items-center justify-center w-full h-20 bg-violet-300 hover:bg-violet-200 rounded-xl dark:bg-violet-700 dark:hover:bg-violet-600 dark:text-gray-300"
>
  {{ $slot }}
</button>
