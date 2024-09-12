<button wire:click.prevent="{{ $click }}" class="flex flex-col items-center justify-center w-full h-20 bg-violet-300 hover:bg-violet-200 rounded-xl">
  {{ $slot }}
</button>
